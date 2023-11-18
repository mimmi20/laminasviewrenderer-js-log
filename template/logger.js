/**
 * @param {Object} errorData
 */
function logErrorData(errorData) {
  const headers = new Headers({
    'Content-Type': "application/json; charset=UTF-8",
    'Content-Length': content.length.toString()
  });

  const request = new Request(
    '%s',
    {
      method: 'POST',
      headers: headers,
      mode: 'cors',
      cache: 'no-cache',
      body: JSON.stringify(errorData),
      referrerPolicy: 'no-referrer'
    }
  );

  fetch(request)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      return response.blob();
    })
    .catch(function(error) {
      console.error(error);
    });
}

window.addEventListener('error', error => {
  let errorData = {
    message: error.message,
    type: error.type.toString(),
    filename: error.filename ?? null,
    element: null,
    url: window.location.href
  };

  // If target is HTML Element save it
  if (error.target.nodeName) {
    errorData.element = error.target.outerHTML;
  }

  // Check if error is Missing SOURCE
  if (error.target.nodeName === 'IMG' || error.target.nodeName === 'SOURCE' || error.target.nodeName === 'IFRAME' || error.target.nodeName === 'VIDEO') {
    errorData.message = 'Invalid or missing source';
  }

  logErrorData(errorData);
}, true);

window.addEventListener('unhandledrejection', event => {
  let errorData = {
    message: event.reason.stack.toString(),
    type: event.reason.name ?? "Promise error",
    filename: null,
    element: null,
    url: window.location.href
  };

  if (event.reason?.response.status) {
    errorData.message = `Status: ${event.reason?.response.status} ${event.reason?.response.url} ${event.reason.stack.toString()}`
  }

  logErrorData(errorData);
  event.preventDefault();
});
