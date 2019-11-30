//将传来的十进制数字转换为8位长度十六进制数（不足补0）得到dataToOx，将转换后的数dataToOx分为两两一组（如：12345678，两两一组分成4组,转换后结果为56781234），
//第一组与第三组交换位置，第二组与第四组交换位置得到dataConvert，再将dataConvert转换为十进制数，返回结果。
//参数：需转换的十进制数字
function dataHexConversion(data){
    // 将data转换为十六进制数
    var dataToOx = parseInt(data.trim()).toString(16);
    // 得到十六进制数的长度
    var len = dataToOx.length;
    // 长度不足8位右补0
    while( len < 8 ) {
        dataToOx  = "0" + dataToOx;
        len = len + 1 ;
    }
    // dataToOx第一组与第三组交换位置，第二组与第四组交换位置得到dataConvert，
    var dataConvert = dataToOx.substr(4,2) + dataToOx.substr(6,2) + dataToOx.substr(0,2) + dataToOx.substr(2,2);
    // dataConvert转换为十进制数，返回结果
    return parseInt(dataConvert,16);
}
