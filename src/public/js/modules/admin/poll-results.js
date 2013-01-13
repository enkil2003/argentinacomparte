var ResultsPoll={
    loadConfirmationModal:function(modalId,o1,o2,o3,o4,r1,r2,r3,r4){
        var $modal=jQuery('#results-'+modalId);
        if($modal.size() === 0){
            var modalString= '<div style="width:500px;padding:10px" id="results-'+modalId+
                  '" class="modal hide fade">'+
                  '<p>'+o1+' || votos:'+r1+'</p>'+
                  '<p>'+o2+' || votos:'+r2+'</p>'+
                  '<p>'+o3+' || votos:'+r3+'</p>'+
                  '<p>'+o4+' || votos:'+r4+'</p>'+
                  '</div>' ;
            $modal=jQuery(modalString);
            }
        $modal.modal('show');
        return false;
        }

};