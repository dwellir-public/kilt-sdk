<?php

namespace KiltSdkPhp;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\InputStream;
use KiltSdkPhp\Exceptions\CommunicationErrorException;
use KiltSdkPhp\Responses\ResponseFactory;
use KiltSdkPhp\Requests\BaseRequest;
use KiltSdkPhp\Responses\QueryByWeb3NameResponse;
use KiltSdkPhp\Responses\BaseResponse;

class ProcessHelper
{
    public function __construct()
    {
        $this->input = new InputStream();
        $pwd = dirname(__FILE__);
        $this->process = new Process([ $pwd . '/../../vendor/bin/node', $pwd . '/../SdkJsWrapper/SdkJsWrapper.js']);
        $this->process->setInput($this->input);
        $this->process->start();
        $this->stderr = fopen('php://stderr', 'w');
    }

    private InputStream $input;
    private Process $process;
    private int $counter = 1;
    private $stderr;

    public function transmit(BaseRequest $request): BaseResponse
    {
        $request->setId($this->counter);
        $this->counter++;

        //newline on end _is_ required
        $this->input->write(base64_encode(json_encode($request, JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR)) . "\n");

        $this->process->waitUntil(function ($type, $buffer) {
            if (Process::ERR === $type) {
                fwrite($this->stderr, 'ERR > ' . $buffer);
                return false;
            }
            $decoded = base64_decode($buffer, true);
            if ($decoded === false) {
                fwrite($this->stderr, 'ERR > ' . $buffer);
                return false;
            }

            // check that we got the whole line
            try {
                json_decode($decoded, false, 512, JSON_THROW_ON_ERROR);
            } catch (\Exception $e) {
                return false;
            }
            return true;
        });

        $response = $this->process->getOutput();
        $rows = explode("\n", $response);

        foreach ($rows as $row) {
            $decoded = base64_decode($row, true);
            if ($decoded === false || trim($decoded) === "") {
                continue;
            }

            $res = json_decode($decoded, false, 512, JSON_THROW_ON_ERROR);

            // it is possible to use getIncrementalOutput or clearOutput
            // to clear out old messages, making it possible to no longer have to
            // iterate over old messages, but one must then also make sure
            // that there are no newer messages in the pipeline!
            if ($res->id != $request->getId()) {
                continue;
            }

            return ResponseFactory::newResponse($res);
        }

        throw new CommunicationErrorException('No response from backend (is node running?)');
    }
}
