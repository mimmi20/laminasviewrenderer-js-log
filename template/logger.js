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
    .catch(error => {
      console.error(error);
    });
}

window.addEventListener('error', event => {
  const errorData = {
    message: event.message,
    type: event.type.toString(),
    filename: event.filename ?? null,
    element: null,
    url: window.location.href
  };

  // If target is HTML Element save it
  if (event.target.nodeName) {
    errorData.element = event.target.outerHTML;
  }

  // Check if error is Missing SOURCE
  if (event.target.nodeName === 'IMG' || event.target.nodeName === 'SOURCE' || event.target.nodeName === 'IFRAME' || event.target.nodeName === 'VIDEO') {
    errorData.message = 'Invalid or missing source';
  }

  logErrorData(errorData);
  event.preventDefault();
});

window.addEventListener('unhandledrejection', event => {
  const errorData = {
    message: event.reason.stack.toString(),
    type: event.reason.name ?? 'Promise error',
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

window.addEventListener('messageerror', event => {
  const errorData = {
    message: event.toString(),
    type: 'Message error',
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
