/*global sessionResults, $, baseUrl, jQuery, alert*/

/**
 * Provisional: print a table with the results.
 */
function printTable() {
    'use strict';
    var tbl = $("<table/>").attr("id", "mytable");
    $("#results").append(tbl);
    jQuery.each(sessionResults, function (i, val) {
        var tr = "<tr>";
        var td1 = "<td>" + i + "</td>";
        var td2 = "<td>" + val + "</td></tr>";
        $("#mytable").append(tr + td1 + td2);
    });
}

$(document).ready(function () {
    'use strict';
    printTable();
});