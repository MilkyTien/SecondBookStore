function readURL(input){
    // 選擇讀入的第一個圖片檔
    if(input.files && input.files[0]){
        var reader = new FileReader();

        reader.onload = function (e){
            // 將頭像換成被選取圖片，並設為背景
            $(".imagePreview").css(
                "background",
                `url(${e.target.result}) no-repeat center/cover`
            );
            // 隱藏原始頭像
            $(".imagePreview").hide();
            // 設定隱藏動畫
            $(".imagePreview").fadeIn(1500);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$("#uploadImg").change(function (){
    // 呼叫 readURL 函式
    readURL(this);
});