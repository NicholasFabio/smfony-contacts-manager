const $ = require('jquery');

function removeContact(id){
    alert(id);
     $.ajax({  
        url:        '/api/contacts/remove/'+id,  
        type:       'DELETE',   
        dataType:   'json',  
        async:      true,  
        
        success: function(data, status) {  
            alert('Successfully Removed Contact');
        },  
        error : function(xhr, textStatus, errorThrown) {  
            alert('Could Not Perform Request.');  
        }  
    });   
}   

// A $( document ).ready() block.
$( document ).ready(function() {
    var urlReq = $(location).attr('href');
    var parts = urlReq.split("/");
    var last_part = parts[parts.length-1];
    // just incase the first request failed it will add a '?' to the URL
    var id = last_part.replace("?", "");

    $('.update-contact').on( "click", function() {
        var name = $('.update-name').val();
        var email = $('.update-email').val();
        var gender = $('.update-gender').val();
        var content = $('.update-content').val();
        var dataOb = {
            "id" : id,
            "name" : name,
            "email" : email,
            "gender": gender,
            "content" : content 
        }

        $.ajax({  
            url:        '/api/contacts/update',  
            type:       'POST',   
            dataType:   'json',  
            data:       JSON.stringify(dataOb),
            async:      true,  
            
            success: function(data, status) {  
               alert('Successfully Updated Contact');
               $( ".go-back" ).click();
            },  
            error : function(xhr, textStatus, errorThrown) {  
               alert('Could Not Perform Request.');  
            }  
         });   
    });  

    $('.add-contact').on( "click", function() {

        var name = $('.add-name').val();
        var email = $('.add-email').val();
        var gender = $('.add-gender').val();
        var content = $('.add-content').val();

        var dataOb = {
            "name" : name,
            "email" : email,
            "gender": gender,
            "content" : content 
        }

        $.ajax({  
            url:        '/api/contacts/add',  
            type:       'POST',   
            dataType:   'json',  
            data:       JSON.stringify(dataOb),
            async:      true,  
            
            success: function(data, status) {  
               alert('Successfully Added Contact');
               $( ".go-back" ).click();
            },  
            error : function(xhr, textStatus, errorThrown) {  
               alert('Could Not Perform Request.');  
            }  
         });   
    });

    
});