// ========= Global JavaScript ========= //

document.addEventListener("DOMContentLoaded", function() {
    // Table Sorting
    document.querySelectorAll("th").forEach(header => {
        header.addEventListener("click", function() {
            let table = header.closest("table");
            let rows = Array.from(table.querySelectorAll("tr:nth-child(n+2)"));
            let index = Array.from(header.parentNode.children).indexOf(header);
            let ascending = header.dataset.order === "asc";
            
            rows.sort((rowA, rowB) => {
                let cellA = rowA.children[index].innerText;
                let cellB = rowB.children[index].innerText;
                return ascending ? cellA.localeCompare(cellB, undefined, {numeric: true}) : cellB.localeCompare(cellA, undefined, {numeric: true});
            });
            
            rows.forEach(row => table.appendChild(row));
            header.dataset.order = ascending ? "desc" : "asc";
        });
    });
});

