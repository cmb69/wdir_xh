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

// @ts-check

document.querySelectorAll("table.wdir_table").forEach((element) => {
    if (!(element instanceof HTMLTableElement)) return;
    widget(element);
});

/** @param {HTMLTableElement} table */
function widget(table) {
    if (!(table instanceof HTMLTableElement)) return;
    const config = JSON.parse(table.dataset.config || "{}");
    const headings = table.querySelectorAll("th");
    headings.forEach(function (heading, index) {
        if (heading.className === config.column) {
            heading.className = config.ascending ? "wdir_asc" : "wdir_desc";
        } else {
            heading.className = "wdir_ascdesc";
        }
        heading.onclick = function () {
            headings.forEach(function (heading2) {
                if (heading2 !== heading) {
                    heading2.className = "wdir_ascdesc";
                }
            });
            if (heading.className === "wdir_asc") {
                heading.className = "wdir_desc";
                sort(index, true);
            } else {
                heading.className = "wdir_asc";
                sort(index, false);
            }
        };
    });

    /**
     * @param {number} column
     * @param {boolean} desc
     */
    function sort(column, desc) {
        const tbody = table.tBodies[0];
        const comparator = column === 0 ? compareString : compareNumber;
        let rows = Array.from(tbody.rows).map(function (tr) {
            return {
                value: tr.getElementsByTagName("td")[column].dataset.wdir || "",
                element: tr
            };
        });
        rows = rows.sort(comparator);
        if (desc) {
            rows = rows.reverse();
        }
        rows.forEach(function (value) {
            tbody.appendChild(value.element);
        });
    }

    /**
     * @param {{value:string,element:HTMLTableRowElement}} a
     * @param {{value:string,element:HTMLTableRowElement}} b
     */
    function compareString(a, b) {
        if (config.caseInsensitive) {
            return a.value.toLowerCase() === b.value.toLowerCase() ? 0 : a.value.toLowerCase() < b.value.toLowerCase() ? -1 : 1;
        }
        return a.value === b.value ? 0 : a.value < b.value ? -1 : 1;
    }

    /**
     * @param {{value:string,element:HTMLTableRowElement}} a
     * @param {{value:string,element:HTMLTableRowElement}} b
     */
    function compareNumber(a, b) {
        return parseInt(a.value) - parseInt(b.value);
    }
}
