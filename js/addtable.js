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

function getOffset(elem) {
  var elemClientRect = elem.getBoundingClientRect();

  return {
    left: elemClientRect.left + window.scrollX,
    top: elemClientRect.top + window.scrollY
  }
}

// Helper function to get an element's exact position
function getPosition(el) {
  var xPos = 0;
  var yPos = 0;

  while (el) {
    if (el.tagName == "BODY") {
      // deal with browser quirks with body/window/document and page scroll
      var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
      var yScroll = el.scrollTop || document.documentElement.scrollTop;

      xPos += (el.offsetLeft - xScroll + el.clientLeft);
      yPos += (el.offsetTop - yScroll + el.clientTop);
    } else {
      // for all other non-BODY elements
      xPos += (el.offsetLeft - el.scrollLeft + el.clientLeft);
      yPos += (el.offsetTop - el.scrollTop + el.clientTop);
    }

    el = el.offsetParent;
  }
  return {
    left: xPos,
    top: yPos
  };
}

function showHideEditMenu(display, callingElemTag, editMenuElemTag){
    if(editMenuElem != "")
    {
        var editMenuElem = document.getElementById(editMenuElemTag);

        if((editMenuElem.style.display == '') || (editMenuElem.style.display == 'none')) {
            var callingElem = document.getElementById(callingElemTag);

            // get location of the current button (x, y)
            var leftOffset = getOffset(callingElem).left;
            var topOffset = getOffset(callingElem).top;

            // set id in the first hidden span.
            var keySpan = editMenuElem.children[0];
            if(keySpan != null)
                keySpan.innerHTML = callingElemTag;

            // show the edit menu div by giving 'display:block'.
            editMenuElem.style.position = "absolute";
            editMenuElem.style.display = "block";

            // Check menu width cross the screen or not.
            // if so then move menu location bit left.
            var diff = window.outerWidth - leftOffset;
            if(diff < editMenuElem.offsetWidth)
                leftOffset = leftOffset - (diff + 5);

            // Check menu height cross the screen or not.
            // if so then move menu location bit top.
            diff = window.outerHeight - topOffset;
            if(diff < editMenuElem.offsetHeight)
                topOffset = topOffset - (editMenuElem.offsetHeight + 1);
            else
                topOffset = topOffset + (callingElem.offsetHeight + 1);

            // put location of the edit menu div bit below to the current button
            editMenuElem.style.left = leftOffset + 'px';
            editMenuElem.style.top = topOffset + 'px';

        } else {
            // hide the edit menu div by giving 'display:none'.
            editMenuElem.style.display = "none";
        }
    }
}
