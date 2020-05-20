</div><!-- end content div -->
<div id="footer" >K9Homes 107 McEwan Road, Heidelberg Heights 3081 Ph: (03) 9455 2277  Fax: (03) 9459 1877</div>
</div><!-- end page container -->

<?php if (0 && $S->isInternalUser()):?>
<script>
        if(geo_position_js.init()){
            geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
        }
        else{
            alert("Functionality not available");
        }

        function success_callback(p)
        {
            //alert('lat='+p.coords.latitude.toFixed(2)+';lon='+p.coords.longitude.toFixed(2));
            oForm = document.forms['geoform'];
            if(typeof(oForm) =="undefined"){
                return;
            }
            oFormLatitude = oForm.elements["latitude"];
            oFormLongitude = oForm.elements["longitude"];
            
            oFormLatitude.value = p.coords.latitude.toFixed(2);
            oFormLongitude.value = p.coords.longitude.toFixed(2);
            
            // write maplink if id=maplink found
            var eMaplink = document.getElementById('maplink');
            eMaplink.innerHTML='<a href="http://maps.google.com.au/maps?ll=' + oFormLatitude.value + ',' + oFormLongitude.value + '" target="_blank" >Google map</a>';
            
            
        }
        
        function error_callback(p)
        {
            //alert('error='+p.code);
        }  
        
        
             
    </script>
<?php endif;?>
</body>
</html>