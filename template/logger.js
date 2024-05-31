/**
 * @param {Object} errorData
 */
function logErrorData(errorData) {
  const content = JSON.stringify(errorData);
  const headers = new Headers({
    'Accept': 'application/json',
    'Content-Type': 'application/json; charset=UTF-8',
    'Content-Length': content.length.toString()
  });

  const request = new Request(
    '%s',
    {
      method: 'POST',
      headers: headers,
      mode: 'cors',
      cache: 'no-cache',
      body: content,
      referrerPolicy: 'no-referrer'
    }
  );

  fetch(request)
    .then(res => res.json())
    .then(data => {
      // enter your logic when the fetch is successful
    })
    .catch(error => {
      console.error(error);
    });
}

window.addEventListener('error', event => {
  let message = event.error.stack ?? event.message;

  // Check if error is Missing SOURCE
  if (event.target.nodeName === 'IMG' || event.target.nodeName === 'SOURCE' || event.target.nodeName === 'IFRAME' || event.target.nodeName === 'VIDEO') {
    message = 'Invalid or missing source';
  }

  const errorData = {
    message: message,
    type: event.type.toString(),
    filename: event.filename ?? null,
    line: event.lineno ?? null,
    column: event.colno ?? null,
    element: null,
    url: window.location.href
  };

  // If target is HTML Element save it
  if (event.target.nodeName) {
    errorData.element = event.target.outerHTML;
  }

  logErrorData(errorData);
});

window.addEventListener('unhandledrejection', event => {
  const errorData = {
    message: event.reason.stack.toString(),
    type: event.reason.name ?? 'Promise error',
    filename: null,
    element: null,
    url: window.location.href
  };

  if (event.reason?.response?.status) {
    errorData.message = `Status: ${event.reason?.response.status} ${event.reason?.response.url} ${event.reason.stack.toString()}`
  }

  logErrorData(errorData);
});

window.addEventListener('messageerror', event => {
  const errorData = {
    message: event.toString(),
    type: 'Message error',
    filename: null,
    element: null,
    url: window.location.href
  };

  if (event.reason?.response?.status) {
    errorData.message = `Status: ${event.reason?.response.status} ${event.reason?.response.url} ${event.reason.stack.toString()}`
  }

  logErrorData(errorData);
});
