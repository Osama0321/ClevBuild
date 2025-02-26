$(document).ready(function() {
    $(".select2").select2();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#companyId').select2({
        placeholder: "Select Company",
        allowClear: true
    });
    
    $('#managerId').select2({
        placeholder: "Select Manger",
        allowClear: true
    });

    $('#taskType').select2({
        placeholder: "Select Task Type",
        allowClear: true
    });
    
    $('#companyId').change(function() {
        let company_id = $(this).val();
        if(company_id !== '' && company_id !== '0' && company_id !== ''){
            $('#managerId').empty().append('<option value="-1">Please wait...</option>');
            
            let options = "<option value=''>Select Manager</option>";
            $.ajax({
                url: "http://localhost/clevebuild-stg/public/managers/get-by-company-id",
                type: 'GET',
                data:{'company_id':company_id},
                success:function(response){
                    $('#managerId').empty();
                    if(response.length){
                        $(response).each(function(key,value){
                            options += `<option value="${value.id}">${value.first_name} ${value.last_name}</option>`;
                        });
                    } 
                },
                complete:function(){
                    $('#managerId').html(options);
                    $('#managerId').select2({
                        placeholder: "Select Manger",
                        allowClear: true
                    });
                }

            })
        } else {
            $("#managerId").empty().append('<option value="">Select Manager</option>');
        }
    });

    $('#countryId').on('change', function() {
        var selectedId = $(this).val();
        if(selectedId !== '' && selectedId !== '0' && selectedId !== ''){
            $("#CityId").empty().append('<option value="">Please wait...</option>');
            $.ajax({
                url: '/projects/getcities/' + selectedId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#CityId').empty();
                    $('#CityId').append('<option value="">Select City</option>')
                    $.each(data, function(index, item) {
                        $('#CityId').append('<option value="' + item.city_id + '">' + item.city_name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $("#CityId").empty().append('<option value="">Select City</option>');
        }
    });

 
    // For Get followers in invoice create
    $('#project_id').on('change', function() {
        var selectedId = $(this).val();
        $("#follower_id").empty().append('<option value="">Please wait...</option>');
        $.ajax({
            url: '/invoices/getFollowers/' + selectedId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#follower_id').empty();
                $('#tasks_details').empty();
                $('#follower_id').append('<option value="">Select Followers</option>')
                $.each(data.followers, function(index, item) {
                    $('#follower_id').append('<option value="' + item.id + '">' + item.first_name +' '+ item.last_name + '</option>');
                });
                $.each(data.tasks, function(index, item) {
                    $('#tasks_details').append('<tr><td>' + item.task_name +'</td><td><input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value=""></td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // For Get tasks in invoice create
    $('#project_id_task_amount').on('change', function() {
        var selectedId = $(this).val();
        $.ajax({
            url: '/addtaskamount/getTasks/' + selectedId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#tasks_amount_details').empty();
                $.each(data.tasks, function(index, item) {
                    $('#tasks_amount_details').append('<tr><td><input type="hidden" name="task_ids[]" value="'+item.task_id+'">' + item.task_name +'</td><td><input type="number" name="task_amounts[]" class="form-control" placeholder="Enter Amount" value="'+item.task_amount+'"></td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $(document).off("click",".submit").on("click",".submit",function(){
        check_validation(function (flag) {
            if (flag) {
                $("#overlay, #loader").css({'display':'block'});
                $("#form").submit();
            }
        });
    });

    $('#taskType').change(function() {
        let task_type = $(this).val();
        if(task_type !== '' && task_type !== '0' && task_type !== ''){
            $('#status').empty().append('<option value="">Please wait...</option>');
            
            let options = "<option value=''>Select Status</option>";
            $.ajax({
                url: "/tasks/getStatusByTaskType",
                type: 'GET',
                data:{'task_type':task_type},
                success:function(response){
                    $('#status').empty();
                    if(response.length){
                        $(response).each(function(key,value){
                            options += `<option value="${value.status_id}">${value.status_name}</option>`;
                        });
                    } 
                },
                complete:function(){
                    $('#status').html(options);
                    $('#status').select2({
                        placeholder: "Select Status",
                        allowClear: true
                    });
                }

            })
        } else {
            $("#status").empty().append('<option value="">Select Status</option>');
        }
    });
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function check_validation(callback){
    flag = true;
    $(".form_validation").each(function(index, elem) {
        var errorDiv = $(this).nextAll("div.text-danger:first");
        if($(this).attr('field_type') == 'text' || $(this).attr('field_type') == 'date')
        {
            if($(this).val() == ''){
                flag = false;
                errorDiv.text($(this).attr('error_val'));
            }
            else{
                errorDiv.empty();
            }
        }
        else if($(this).attr('field_type') == 'select')
		{
			if($(this).find(":selected").val() == '')
			{
				flag = false;
                errorDiv.text($(this).attr('error_val'));
			}else{
                errorDiv.empty();
            }
		}
        else if($(this).attr('field_type') == 'multi_select')
		{
            if($(this).val().length == 0)
			{
				flag = false;
                errorDiv.text($(this).attr('error_val'));
			}else{
                errorDiv.empty();
            }
        }
        else if($(this).attr('field_type') == 'email'){
            if($(this).val() == ''){
                flag = false;
                errorDiv.text('The email field is required');
            }else{
                if( !validateEmail($(this).val()) ) {
                    flag = false;
                    errorDiv.text('The email must be a valid email address');
                }else{
                    errorDiv.empty();
                }
            }
        }
    })
    callback(flag);
}

function validatePhoneNumber(input) {
    let value = input.value;

    // Remove all non-numeric and non-plus characters
    value = value.replace(/[^0-9+]/g, '');

    // Ensure the plus sign is always at the start if present
    if (value.includes("+")) {
        value = "+" + value.replace(/\+/g, ""); // Keep only the first plus sign
    }
    input.value = value;
}