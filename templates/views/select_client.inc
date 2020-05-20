<?php

function client_selector_html($clients) {
    foreach ($clients as $x =>$c) {
        if(!empty($c['name'])){
            echo "<option value=\"".$c['client_id']."\" >".$c['name']."</option>\n"; 
        }
    }
}
$cArr=array();
foreach($clients as $c ){
    if(!empty($c['name'])){
        $cArr [$c['client_id']]=  $c['name'] ;
    }

}
//echo dumper($cArr);
echo '<script type="text/javascript">';
echo 'var clients='.safe_json_encode($cArr).';';
echo '</script>';
?>


<form id="client_select_form" method="post" action="" >

    <table>
        <tr>
            <td colspan=3><h3>Select the client you want to work with</h3></td>
        </tr>
        <tr>
            <td>Clients:</td>
            <td >
                <div class="ui-widget">
                    <input type="hidden" name="e" value="SelectClient">
                    <input type="hidden" id="client_id" name="client_id">
                    <xinput type="submit" name="e" value="SelectClient" style="margin-bottom:10px;"> 

                <div id="client_selector" >
                    <input id="client_input" name="client_name" class="typeahead" type="text" placeholder="Clients list">
                     
                </div>
                </div>
            </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>

</form>	
<style>
    .ui-button { margin-left: -1px; }
    .ui-button-icon-only .ui-button-text { padding: 0.35em; } 
    .ui-autocomplete-input { margin: 0; padding: 0.48em 0 0.47em 0.45em; }
</style>
<script>  

    $(document).ready(function(){
        //console.log(clients);
        
        $('#client_input').bind('typeahead:selected', function(obj,datum,name){
         //console.log(datum)  ;
         $('#client_id').val(datum['key']);
         $('#client_select_form').trigger('submit');
         
         
        //alert(JSON.stringify(datum)); // contains datum value, tokens and custom fields
        // outputs, e.g., {"redirect_url":"http://localhost/test/topic/test_topic","image_url":"http://localhost/test/upload/images/t_FWnYhhqd.jpg","description":"A test description","value":"A test value","tokens":["A","test","value"]}
        // in this case I created custom fields called 'redirect_url', 'image_url', 'description'   

        //alert(JSON.stringify(name)); // contains dataset name
        // outputs, e.g., "my_dataset"
        });
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                //console.log(q);
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({ value: str ,key:i});
                    }
                });

                cb(matches);
            };
        };

        $('#client_selector .typeahead').typeahead(
            {
                hint: true,
                highlight: true,
                minLength: 2
                
            },
            {
                name: 'clients',
                displayKey: 'value',
                source: substringMatcher(clients)
            }

        );

    });  
  

    (function( $ ) {
        $.widget( "ui.combobox", {
            _create: function() {
                var self = this,
                select = this.element.hide(),
                selected = select.children( ":selected" ),
                value = selected.val() ? selected.text() : "";
                var input = this.input = $( "<input>" )
                .insertAfter( select )
                .val( value )
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: function( request, response ) {
                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                        response( select.children( "option" ).map(function() {
                            var text = $( this ).text();
                            if ( this.value && ( !request.term || matcher.test(text) ) )
                                return {
                                    label: text.replace(
                                        new RegExp(
                                            "(?![^&;]+;)(?!<[^<>]*)(" +
                                            $.ui.autocomplete.escapeRegex(request.term) +
                                            ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                        ), "<strong>$1</strong>" ),
                                    value: text,
                                    option: this
                                };
                        }) );
                    },
                    select: function( event, ui ) {
                        ui.item.option.selected = true;
                        self._trigger( "selected", event, {
                            item: ui.item.option
                        });
                    },
                    change: function( event, ui ) {
                        if ( !ui.item ) {
                            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                            valid = false;
                            select.children( "option" ).each(function() {
                                if ( $( this ).text().match( matcher ) ) {
                                    this.selected = valid = true;
                                    return false;
                                }
                            });
                            if ( !valid ) {
                                // remove invalid value, as it didn't match anything
                                $( this ).val( "" );
                                select.val( "" );
                                input.data( "autocomplete" ).term = "";
                                return false;
                            }
                        }
                    }
                })
                .addClass( "ui-widget ui-widget-content ui-corner-left" );

                input.data( "autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li></li>" )
                    .data( "item.autocomplete", item )
                    .append( "<a>" + item.label + "</a>" ).attr({href: '#'})
                    .appendTo( ul );
                };

                this.button = $( "<button type='button'>&nbsp;</button>" )
                .attr( "tabIndex", -1 )
                .attr( "title", "Show All Items" )
                .insertAfter( input )
                .button({
                    icons: {
                        primary: "ui-icon-triangle-1-s"
                    },
                    text: false
                })
                .removeClass( "ui-corner-all" )
                .addClass( "ui-corner-right ui-button-icon" )
                .click(function() {
                    // close if already visible
                    if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                        input.autocomplete( "close" );
                        return;
                    }

                    // work around a bug (likely same cause as #5265)
                    $( this ).blur();

                    // pass empty string as value to search for, displaying all results
                    input.autocomplete( "search", "" );
                    input.focus();
                });
            },

            destroy: function() {
                this.input.remove();
                this.button.remove();
                this.element.show();
                $.Widget.prototype.destroy.call( this );
            }
        });
    })( jQuery );

    $(function() {
        $( "#combobox" ).combobox();
        $( "#toggle" ).click(function() {
            $( "#combobox" ).toggle();
        });
    });
</script>