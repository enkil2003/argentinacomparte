var MyDialogs2={
    loadConfirmationModal:function(modalId, image){
        var $modal=jQuery('#img-'+modalId);
        if($modal.size() === 0){
            var modalString= '<img style="width:140px" id="img-'+modalId+'" class="modal hide fade" src="/uploads/banners/'+image+'">' ;
            $modal=jQuery(modalString);
            }
        $modal.modal('show');
        return false;
        }

};