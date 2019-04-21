/**
 * Send a Post Request to reset search Action.
 */

const $ = global.$;

const init = function resetSearch(url, redirectUrl) {
    $.post(url).then(() => window.location.assign(redirectUrl));
};

export default init;
