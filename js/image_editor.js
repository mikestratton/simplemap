$(document).ready(function() {
        //slider for opacity
        $('#opacity').slider({ 
        min: 0, 
        max: 1, 
        step: 0.01, 
        value: 1,
        orientation: "horizontal",
             slide: function(e,ui){
                     $('#box1').css('opacity', ui.value)

             }                
        })
		
		//slider for rotation  
		$('#rotate').slider({ 
        min: 0, 
        max: 360, 
        step: 0.1, 
        value: 1,
        orientation: "horizontal",
             slide: function(e,ui){
                     $('#box2').rotate(ui.value)

             }                
        })
		
		//slider for height
        $('#height').slider({ 
        min: 0, 
        max: 1000, 
        step: 0.01, 
        value: 1,
        orientation: "horizontal",
             slide: function(e,ui){
                     $('#box3').css('height', ui.value)

             }                
        })
		
		//slider for width
        $('#width').slider({ 
        min: 0, 
        max: 1000, 
        step: 0.01, 
        value: 1,
        orientation: "horizontal",
             slide: function(e,ui){
                     $('#box3').css('width', ui.value)

             }                
        })
		
    });
</script>