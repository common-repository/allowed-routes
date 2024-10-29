(function($) {
    insertLineCountsToSelect();

    $( "#button_newroute" ).click(function() {
        addRoute();    
    });
    
    $( "#button_moreexamples" ).click(function() {
        $(window).scrollTop(0);
        showHelpTab();    
    });
    
    $( "#button_more" ).click(function() {
        $(window).scrollTop(0);
        showHelpTab();    
    });
    
    $('#button_removeselectedroutes').click(function() {
        removeRoute();
    });
    
    $('#tabRouting').click(function() {
        showRoutingTab();
    });

    $('#tabHelp').click(function() {
        showHelpTab();
    });
    
    $('#checkbox_allowindexpage').change(function()
    {
            if(this.checked) {
                $('#select_routes').append($('<option>', { value: 'customroute', text: '/' }));
            }
            else {
                $("#select_routes > option").each(function() {
                    if(this.text == '/') {
                        $(this).remove();
                    }
                });
            }
            updateHiddenRoutes();
    });    

    
    $('#checkbox_enablerouting').change(function()
    {
        alert('Please click "Apply Changes" before changes take effect. Also make sure you clear all page caches afterwards.');
        if(this.checked) {
            $('#enabled').attr('value', '1');
        }
        else {
            $('#enabled').attr('value', '0');
        }
    });    
    
    $('#input_newroute').bind("enterKey", function(e)
    {
       addRoute();
    });
    
    $('#input_newroute').keyup(function(e)
    {
        if(e.keyCode == 13) {
            $(this).trigger("enterKey");
        }
    });
    
    function removeRoute()
    {
        $('#select_routes  option:selected').each(function() {
            if($(this).text() == '/') {
                $('#checkbox_allowindexpage').prop('checked', false);
            }
            $(this).remove();
        });        
        updateHiddenRoutes();
        insertLineCountsToSelect();
    }
    
    function updateHiddenRoutes()
    {
        $('#div_hiddenroutes').empty();
        $("#select_routes > option").each(function() {
            if(this.value == 'customroute') {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'routes[]',
                    value: this.text
                }).appendTo('#div_hiddenroutes');                    
            }
        });            
    }

    function insertLineCountsToSelect()
    {
        return;
        var lineCnt = 1;
        var cntLength = 2;
        $("#select_routes > option").each(function() {
            if (this.text.charAt(0) == '#' && this.text.charAt(3) == ':' && this.text.charAt(4) == ' ') {
                $(this).text('#' + pad(lineCnt, cntLength) + ': ' +  this.text.substring(5));
            }
            else {
                $(this).text('#' + pad(lineCnt, cntLength) + ': ' +  this.text);    
            }
            lineCnt = lineCnt + 1;
        });
    }
    
    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }    
    
    function addRoute()
    {
        if (!$('#input_newroute').val().trim()) {
            /* empty or whitespaces */
        }
        else {
            
            if($('#input_newroute').val() == '/') {
                $('#checkbox_allowindexpage').prop('checked', true);
            }
            
            $('#select_routes').append($('<option>', { value: 'customroute', text: $('#input_newroute').val() }));
            $('#input_newroute').val('');
            updateHiddenRoutes();
            insertLineCountsToSelect();
        }
    }
    
    function showRoutingTab()
    {
        $('#tab1content').show();
        $('#tab1sidebar').show();
        $('#tab2content').hide();
        $('#tab2sidebar').hide();
        $("#tabRouting").addClass( "nav-tab-active" );
        $("#tabHelp").removeClass( "nav-tab-active" );
    }
    
    function showHelpTab()
    {
        $('#tab2content').show();
        $('#tab2sidebar').show();
        $('#tab1content').hide();
        $('#tab1sidebar').hide();
        $("#tabHelp").addClass( "nav-tab-active" );
        $("#tabRouting").removeClass( "nav-tab-active" );
    }
    
	
})( jQuery );



    
    
    
     
    


