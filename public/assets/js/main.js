$(document).ready(function () {
    //Sidebar Menu
    $('.sidebar-menu').tree();

    //Table
    $('#table').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false
    });

    //Select All Checkbox into table
    $('#check_all_checkbox').on('click', function(e) {

        if($(this).is(':checked',true)){
            $(".sub_chk").prop('checked', true);  
        }else{  
            $(".sub_chk").prop('checked',false);  
        }  
    });

    //Bulk Action Event
    $('.bulk_action').on('change', function(e) {
        var selectedValue = $(this).val();

        if(selectedValue == -1){
            return false;
        }
            
        var allVals = [];  
        $(".sub_chk:checked").each(function() {  
            allVals.push($(this).attr('data-id'));
        });  

        if(allVals.length <=0)   
        {  
            alert("Please select row.");  
        }  else {  
            var check = confirm("Are you sure you want to delete this row?");  



            if(check == true){  
                var join_selected_values = allVals.join(","); 
                
                $.ajax({
                    url: $(this).data('url'),
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids='+join_selected_values+'&action='+selectedValue,
                    success: function (data) {
                        if (data['success']) {
                            $(".sub_chk:checked").each(function() {  
                                $(this).parents("tr").remove();
                            });
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });

              $.each(allVals, function( index, value ) {
                  $('table tr').filter("[data-row-id='" + value + "']").remove();
              });
            }  
        }  
    });


    //SINGLE DELETE

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function (event, element) {
            element.trigger('confirm');
        }
    });


    $(document).on('confirm', function (e) {
        var ele = e.target;
        e.preventDefault();
        $.ajax({
            url: ele.href,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data['success']) {
                    $("#" + data['tr']).slideUp("slow");
                    alert(data['success']);
                } else if (data['error']) {
                    alert(data['error']);
                } else {
                    alert('Whoops Something went wrong!!');
                }
            },
            error: function (data) {
                alert(data.responseText);
            }
        });


        return false;
    });

});

function remove_single_record(string, id, action, url){
   
        var check = confirm("Are you sure you want to delete this row?");  
            if(check == true){  
                debugger;           
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids='+id+'&action='+action,
                    success: function (data) {
                        if (data['success']) {
                            $("table #"+string+id).remove();
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });

              /*$.each(allVals, function( index, value ) {
                  $('table tr').filter("[data-row-id='" + value + "']").remove();
              });*/
            }

}


function remove_object_image(object, object_field, object_id, url){

    var check = confirm("Are you sure you want to delete this image");  
    
    if(check == true){

        $.ajax({

            url:url,
            type:'DELETE',
            headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data:'object='+object+'&object_field='+object_field+'&object_id='+object_id,
            success:function(data){
                alert(data.success);
                location.reload();
            }

        });
    }
}
