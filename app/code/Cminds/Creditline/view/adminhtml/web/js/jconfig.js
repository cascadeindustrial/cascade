require([
    'jquery',
    'jquery/ui',
], function($) {
    $(document).ready(function(){
        getEventAction();
        $('#creditline_general_creditline_select').change(function(){
            getEventAction();
        });
        function getEventAction(){
            var paymentType = $('#creditline_general_creditline_select option:selected').val();
            if(paymentType === '2'){
                $('#row_creditline_general_number_of_days').show();
            }else{
                $('#row_creditline_general_number_of_days').hide();
            }
        }
    });
});
