import * as kilt from '@kiltprotocol/sdk-js';
import * as readline from 'readline';

/* eslint-disable no-console */

const debug = 0;

function formatFailResponse(inputData, errorCode, errorMessage) {
  const res = JSON.stringify({
    status: 'error',
    id: inputData?.id,
    function: inputData?.function,
    parameters: inputData?.parameters,
    error: errorCode,
    message: errorMessage,
  });
  if (debug) {
    console.log(res);
    return;
  }

  // https://www.nhs.uk/mental-health/self-help/guides-tools-and-activities/breathing-exercises-for-stress/
  console.log(btoa(unescape(encodeURIComponent((res)))));
}

function formatSucessResponse(inputData, response) {
  const res = JSON.stringify({
    status: 'success',
    id: inputData.id,
    function: inputData.function,
    parameters: inputData?.parameters,
    response,
  });
  if (debug) {
    console.log(res);
    return;
  }
  console.log(btoa(unescape(encodeURIComponent((res)))));
}

async function verifyDidSignature(inputData) {
  await kilt.Did.verifyDidSignature({
    expectedSigner: inputData?.expectedSigner,
    keyUri: inputData?.keyUri,
    message: inputData?.message,
    signature: inputData?.signature,
  });
  return true;
}

function main() {
  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
    terminal: false,
  });

  rl.on('line', (line) => {
    let inputData;
    try {
      inputData = JSON.parse(atob(line.trim()));
    } catch {
      formatFailResponse(null, 'unableToDecodeInput', 'unable to decode input string');
      return;
    }

    if (typeof inputData.id !== 'number') {
      formatFailResponse(inputData, 'idMissing', 'id parameter is missing from input or not an integer');
      return;
    }

    if (typeof inputData.function !== 'string') {
      formatFailResponse(inputData, 'functionMissing', 'function parameter is missing from input');
      return;
    }

    try {
      switch (inputData.function) {
        case 'did.queryByWeb3Name':
          kilt.ConfigService.get('api').call.did.queryByWeb3Name(inputData?.parameters?.name)
            .catch((e) => { formatFailResponse(inputData, e.name, e.message); })
            .then((val) => { if (typeof val !== 'undefined') formatSucessResponse(inputData, val); });
          break;

        case 'did.resolve':
          kilt.Did.resolve(inputData?.parameters?.did)
            .catch((e) => { formatFailResponse(inputData, e.name, e.message); })
            .then((val) => { if (typeof val !== 'undefined') formatSucessResponse(inputData, val); });
          break;

        case 'did.verifyDidSignature':
          verifyDidSignature(inputData?.parameters)
            .catch((e) => { formatFailResponse(inputData, e.name, e.message); })
            .then((val) => { if (typeof val !== 'undefined') formatSucessResponse(inputData, null); });
          break;

        case 'connect':
          kilt.connect(inputData?.parameters?.blockchainRpcWsUrl)
            .catch((e) => { formatFailResponse(inputData, e.name, e.message); })
            .then((val) => { if (typeof val !== 'undefined') formatSucessResponse(inputData, null); });
          break;

        case 'disconnect':
          kilt.disconnect()
            .catch((e) => { formatFailResponse(inputData, e.name, e.message); })
            .then((val) => { if (typeof val !== 'undefined') formatSucessResponse(inputData, val); });
          break;

        case 'test':
          formatSucessResponse(inputData, null);
          break;

        case 'exit':
          formatSucessResponse(inputData, null);
          process.exit();
          break;

        default:
          formatFailResponse(inputData, 'functionNotSupported', 'The selected function is not supported yet');
      }
    } catch (e) {
      formatFailResponse(inputData, e.name, e.message);
    }
  });
}

main();
