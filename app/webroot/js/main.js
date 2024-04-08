$(document).ready(function() {
    $("#UserBirthday").datepicker({
        dateFormat: "yy-mm-dd"
    });

    $("#UserPhoto").change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#photo-preview").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});