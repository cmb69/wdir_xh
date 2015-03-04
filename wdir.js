/*!
 * Wdir_XH.
 *
 * @author  Christoph M. Becker <cmbecker69@gmx.de>
 * @license GPL-3.0+
 */

/*jslint browser: true, maxlen: 80 */
/*global WDIR */

(function () {
    "use strict";

    /**
     * Returns a list of results of applying a function
     * to each element of a list.
     *
     * @param {Array}    element
     * @param {Function} func
     *
     * @returns {Array}
     */
    function map(elements, func) {
        var i, len, result;

        result = [];
        for (i = 0, len = elements.length; i < len; i += 1) {
            result.push(func(elements[i], i));
        }
        return result;
    }

    /**
     * Calls a function for each element in elements.
     *
     * @param {Array}    elements
     * @param {Function} func
     *
     * @returns {undefined}
     */
    function forEach(elements, func) {
        var i, len;

        for (i = 0, len = elements.length; i < len; i += 1) {
            func(elements[i], i);
        }
    }

    /**
     * Registers an event listener.
     *
     * @param {EventTarget}   element
     * @param {String}        type
     * @param {EventListener} listener
     *
     * @returns {undefined}
     */
    function on(element, type, listener) {
        if (typeof element.addEventListener !== "undefined") {
            element.addEventListener(type, listener, false);
        } else if (typeof element.attachEvent !== "undefined") {
            element.attachEvent("on" + type, listener);
        }
    }

    on(window, "load", function () {
        var headings;

        /**
         * Returns the wdir table heading cells.
         *
         * @return {Array}
         */
        function findTableHeadingCells() {
            var tables, result;

            result = [];
            tables = document.getElementsByTagName("table");
            forEach(tables, function (table) {
                if (table.className === "wdir_table") {
                    var cells;

                    cells = table.tHead.getElementsByTagName("td");
                    forEach(cells, function (cell) {
                        result.push(cell);
                    });
                }
            });
            return result;
        }

        /**
         * Sorts the rows of a table.
         *
         * @param {HTMLTableElement} table
         * @param {Number}           column
         * @param {Boolean}          desc
         *
         * @returns {undefined}
         */
        function sort(table, column, desc) {
            var tbody, rows;

            tbody = table.tBodies[0];
            rows = map(tbody.rows, function (tr) {
                var value;

                value = tr.getElementsByTagName("td")[column]
                        .getAttribute("data-wdir");
                if (column === 0) {
                    if (WDIR.caseInsensitive) {
                        value = value.toLowerCase();
                    }
                } else {
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

                return a.value === b.value ? 0
                        : xor(a.value < b.value, desc) ? -1 : 1;
            });
            forEach(rows, function (value) {
                tbody.appendChild(value.element);
            });
        }

        headings = findTableHeadingCells();
        forEach(headings, function (heading, index) {
            if (index % 3 === 0) {
                heading.className = "wdir_asc";
            } else {
                heading.className = "wdir_ascdesc";
            }
            on(heading, "click", function () {
                var table, headings;

                table = heading;
                while (table.nodeName.toLowerCase() !== "table") {
                    table = table.parentNode;
                }
                headings = table.tHead.getElementsByTagName("td");
                forEach(headings, function (heading2) {
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
}());
