$_debug = false;

/**
 * Checks for session timeout before opening a dialog form and reloads page if session is timed out
 * @param {string} key Description for the value
 * @param {string} value Value to be logged
 * @param {boolean} isObject If is true, then value and key are displayed on different lines
 * @returns {boolean} true
 */
function __log(key, value, isObject) {
    if ($_debug) {
        console.log(key + ' === ' + value);

        if (isObject) {
            console.log(key + ' === ');
            console.log(value);
        }
    }

    return true;
}

$.ajaxSetup({
    error: function(jqXHR, exception) {
        switch (jqXHR.status) {
            case 0: 
                alert('Not connected.\n Verify Network.');
                break;
                
            case 404:
                alert('Requested page not found. [404]');
                break;
                
            case 500:
                alert('Internal Server Error [500].');
                break;
                
            case 401:
                alert('Authorization failed. Please Re-login [401].');
                break;
                
            case 404:
                break;
                
            case 'parseerror':
                alert('Requested JSON parse failed.');
                break;
                
            case 'timeout':
                alert('Time out error.');
                break;
                
            case 'abort':
                alert('Ajax request aborted.');
                break;
                
            default:
                alert('Uncaught Error.\n' + jqXHR.responseText);
                break;
                
        }
    }
});

/**
 * Function Description
 * @param {type} name Description
 * @returns {type} description
 */
function __ajax(url, type, data, __callback, dataType) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: dataType,
        statusCode: {
            404: function() {
                alert('Page Not Found');
            },
            500: function() {
                alert('internal server error');
            }
        },
        dataFilter: function(filterData) {
            console.log(filterData)
            // Filter your data here before passing to success
        }
    }).fail(function($xhr, textStatus, errorThrown) {
        alert('failed');
    }).done(function(data, textStatus, $xhr) {
        alert('done');
    }).always(function() {
        alert('another complete function');
    });
}
