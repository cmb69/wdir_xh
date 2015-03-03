/*global addEventListener */
if (typeof addEventListener === "function") {
    addEventListener("load", function () {
        "use strict";

        var headings;

        function sort(table, column, desc) {
            var tbody, rows;

            tbody = table.querySelector("tbody");
            rows = Array.prototype.map.call(tbody.rows, function (tr, index) {
                var value = tr.getElementsByTagName("td")[column]
                        .getAttribute("data-wdir");
                if (column > 0) {
                    value = +value;
                }
                return {
                    value: value,
                    element: tr
                };
            });
            rows = rows.sort(function (a, b) {
                function xor(a, b) {
                    return (a || b) && !(a && b);
                }

                return a.value === b.value ? 0 : xor(a.value < b.value, desc) ?
                        -1 : 1;
            });
            rows.forEach(function (value) {
                tbody.appendChild(value.element);
            });
        }

        headings = document.querySelectorAll(".wdir_table thead tr td");
        Array.prototype.forEach.call(headings, function (heading, index) {
            if (index % 3 === 0) {
                heading.className = "wdir_asc";
            } else {
                heading.className = "wdir_ascdesc";
            }
            heading.addEventListener("click", function () {
                var table, headings;

                table = heading;
                while (table.nodeName.toLowerCase() !== "table") {
                    table = table.parentNode;
                }
                headings = table.querySelectorAll("thead td");
                Array.prototype.forEach.call(headings, function (heading2) {
                    if (heading2 !== heading) {
                        heading2.className = "wdir_ascdesc";
                    }
                });
                if (heading.className === "wdir_asc") {
                    heading.className = "wdir_desc";
                    sort(table, index % 3, true);
                } else {
                    heading.className = "wdir_asc";
                    sort(table, index % 3, false);
                }
            });
        });
    });
}
