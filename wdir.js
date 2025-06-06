/**
 * Copyright (c) Christoph M. Becker
 *
 * This file is part of Wdir_XH.
 *
 * Wdir_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Wdir_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wdir_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

var config, headings;

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
    rows = Array.from(tbody.rows).map(function (tr) {
        var value;

        value = tr.getElementsByTagName("td")[column]
                .getAttribute("data-wdir");
        if (column === 0) {
            if (config.caseInsensitive) {
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
    rows.forEach(function (value) {
        tbody.appendChild(value.element);
    });
}

function init(table) {
    config = JSON.parse(document.querySelector(".wdir_config").dataset.config);
    headings = table.tHead.querySelectorAll("td");
    headings.forEach(function (heading, index) {
        if (index === 0) {
            heading.className = "wdir_asc";
        } else {
            heading.className = "wdir_ascdesc";
        }
        heading.onclick = function () {
            var table, headings;

            table = heading;
            while (table.nodeName.toLowerCase() !== "table") {
                table = table.parentNode;
            }
            headings = table.tHead.querySelectorAll("td");
            headings.forEach(function (heading2) {
                if (heading2 !== heading) {
                    heading2.className = "wdir_ascdesc";
                }
            });
            if (heading.className === "wdir_asc") {
                heading.className = "wdir_desc";
                sort(table, index, true);
            } else {
                heading.className = "wdir_asc";
                sort(table, index, false);
            }
        };
    });
}

document.querySelectorAll("table.wdir_table").forEach(init);
