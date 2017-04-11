function add_row() {
    var table = document.getElementById("addrow");
    var row = table.insertRow(1);
    var cell1 = row.insertCell(1);
    var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);
    var cell6 = row.insertCell(6);
	var cell7 = row.insertCell(7);
    cell1.innerHTML = " CELL1";
    cell2.innerHTML = " CELL2";
	cell3.innerHTML = " CELL3";
    cell4.innerHTML = " CELL4";
	cell5.innerHTML = " CELL5";
    cell6.innerHTML = " CELL6";
	cell7.innerHTML = " CELL7";
}

function delete_row() {
    document.getElementById("addrow").deleteRow(1);
}

function myFunction() {
    var storyInlineBtnElem = document.getElementById("story-inline-btn");

    var leftOffset = getOffset(storyInlineBtnElem).left;
    var topOffset = getOffset(storyInlineBtnElem).top;

    var d = document.getElementById('storyInlineDropdown');
    d.style.position = "absolute";
    d.style.left = leftOffset+'px';
    d.style.top = (topOffset + storyInlineBtnElem.offsetHeight + 1) + 'px';
    d.style.display = "block";

    //document.getElementById("story-inline-btn").classList.toggle("show");
}

function myFunction1() {
    document.getElementById("myDropdown-edit").style.right = "100px";
}
