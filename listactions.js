

function addRow(listID) {
    var list = document.getElementById(listID);
    var newLI = document.createElement("li");

    var element = document.createElement("input");
    element.type = "text";
    element.size = "80";
    element.name = "task[]";

    newLI.appendChild(element);
    list.insertBefore(newLI, list.lastChild.nextSibling);
    element.focus();
}


// this function doesnt work yet
function deleteRow(listID,itemID) {
  try {
    var list = document.getElementById(listID);
    var item = document.getElementById(itemID);
    var firstRow = list.firstChild;

    function shouldDelete(listID, itemID, row) {
        if (row == document.getElementById(listID).lastChild.nextSibling)
        {}
        else if (row.childNodes[2].id == itemID){
            row.parentNode.removeChild(row);
        }
        else {
            shouldDelete(listID, itemID, row.nextSibling);
        }
    }

    shouldDelete(listID, itemID, firstRow);
  }
  catch(e) {
    alert(e);
  }
}

function enterKeyPress(e,id) {
    // look for window.event in case event isn't passed in
    if (typeof e == 'undefined' && window.event) {
        e = window.event;
    }
    if (e.keyCode == 13) {
        document.getElementById('add'+id).click();
    }
}

function saveTask(itemId,listId,textValue) {
        var mygetrequest=new XMLHttpRequest();
        mygetrequest.onreadystatechange=function(){
            if (mygetrequest.readyState==4){
                if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
                }
                else{
                    alert("An error has occured making the request");
                }
            }
        };
        var taskvalue=encodeURIComponent(itemId);
        var listvalue=encodeURIComponent(listId);
        var textvalue=encodeURIComponent(textValue);
        mygetrequest.open("GET", "additem.php?taskid="+taskvalue+"&listid="+listvalue+"&text="+textvalue, true);
        mygetrequest.send(null);
}


$(function() {
    $('input').keyup(function(e){
        if(e.which==40)      // 40 is down arrow
            $(this).closest('li').next().find('input').focus();
        else if(e.which==38) // 38 is up arrow
            $(this).closest('li').prev().find('input').focus();
    });
});


// this function enables jQuery sortable for the lists with the ids #sortable1 and #sortable2, and allows
// all lists with class connectedSortable to be connected and interoperable
$(function() {
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable", opacity: 0.8, update: function() {
            var order = $(this.value).sortable('serialize') + '&action=updateRecordsListings';
            $.post("updateLists.php", order, function(theResponse){$("#resp").html(theResponse);
        });
        }
    }).disableSelection();
});





























