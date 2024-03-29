function addRow(listID) {
    var list = listID;
    $.post("add.php", {ListID: list}, function(){window.location.reload();});
}

function deleteRow(listID,itemID) {
    var list = listID;
    var item = itemID;
    $.post("delete.php", {ListID: list, TaskID: itemID}, function(){window.location.reload();});
}

function archive(taskID) {
    var task = taskID;
    $.post("archive.php", {TaskID: task}, function(){window.location.reload();});
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
        mygetrequest.open("GET", "saveTask.php?taskid="+taskvalue+"&listid="+listvalue+"&text="+textvalue, true);
        mygetrequest.send(null);
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

$(function() {
    $('input').keyup(function(e){
        if(e.which==40)      // 40 is down arrow
            $(this).closest('li').next().find('input').focus();
        else if(e.which==38) // 38 is up arrow
            $(this).closest('li').prev().find('input').focus();
    });
});

// jQuery sortable, draggable, and droppable
$(function() {
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable", opacity: 1.0, update: function() {
            var order = $(this).sortable('serialize') + '&action=updateRecordsListings';
            $.post("updateLists.php", order);
        }
    }).disableSelection();

    $( ".draggable2" ).draggable({
        revert: "invalid" ,
        containment: 'document',
        helper: 'clone',
        opacity: 0.70,
        zIndex: 10000,
        appendTo: "body"
    });

    $( "#sortable1, #sortable2" ).droppable({
        accept: ".draggable2",
        activeClass: "ui-state-active",
        hoverClass: "ui-state-hover",
        drop: function( event, ui ) {
            $( this ).addClass( "ui-state-highlight" );
            var list = $( this).closest("div").attr("id");
            var mailID = ui.draggable.attr("id");
            $.post("addMail.php", {ListID: list, MailID: mailID}, function(){window.location.reload();});
        }
    });

});

$(function() {
    $( ".column" ).resizable({
        alsoResize: ".bottom",
        handles: "s"
    });
    $( ".bottom" ).resizable();
});

// shows and hides the menu
function showElement(layer){
    var myLayer = document.getElementById(layer);
    if(myLayer.style.display=="none"){
        myLayer.style.display="block";
        myLayer.backgroundPosition="top";
    }
    else {
        myLayer.style.display="none";
    }
}