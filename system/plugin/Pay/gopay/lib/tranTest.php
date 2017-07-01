                     
                   


<form method="POST" action="sign.php">
<table>
    <tr>
        <td>网关版本号:</td>
        <td><input type="text" id="version" name="version" value="2.1" size="50"/></td>
    </tr>
    <tr>
        <td>字符集:</td>
        <td><input type="text" id="charset" name="charset" value="1" size="50"/>1:GBK,2:UTF-8 (不填则当成1处理)</td>
    </tr>
    <tr>
        <td>网关语言版本:</td>
        <td><input type="text" id="language" name="language" value="1" size="50"/>1:ZH,2:EN</td>
    </tr>
    <tr>
        <td>报文加密方式:</td>
        <td><input type="text" id="signType" name="signType" value="1" size="50"/>1:MD5,2:SHA</td>
    </tr>
    <tr>
        <td>交易代码:</td>
        <td><input type="text" id="tranCode" name="tranCode" value="8888" size="50"/></td>
    </tr>
    <tr>
        <td>商户代码:</td>
        <td><input type="text" id="merchantID" name="merchantID" value="0000003358" size="50"/></td>
    </tr>
    <tr>
        <td>（测试时，请修改订单号）订单号:</td>
        <td><input type="text" id="merOrderNum" name="merOrderNum" value="guoguofufu001" size="50"/></td>
    </tr>    
    <tr>
        <td>交易金额:</td>
        <td><input type="text" id="tranAmt" name="tranAmt" value="10.00" size="50"/></td>
    </tr>
    <tr>
        <td>商户提取佣金金额:</td>
        <td><input type="text" id="feeAmt" name="feeAmt" value="1.00" size="50"/></td>
    </tr>
    <tr>
        <td>币种:</td>
        <td><input type="text" id="currencyType" name="currencyType" value="156" size="50"/></td>
    </tr>
    <tr>
        <td>（测试返回地址需要能够外网访问）商户前台通知地址:</td>
        <td><input type="text" id="frontMerUrl" name="frontMerUrl" value="" size="50"/></td>
    </tr>
    <tr>
        <td>（测试返回地址需要能够外网访问）商户后台通知地址:</td>
        <td><input type="text" id="backgroundMerUrl" name="backgroundMerUrl" value="http://127.0.0.1:8080/res.php" size="50"/></td>
    </tr>     
    
    <tr>
        <td>交易时间:</td>
        <td><input type="text" id="tranDateTime" name="tranDateTime" value="20111104094626" size="50"/></td>
    </tr>        
    <tr>
        <td>国付宝转入账户:</td>
        <td><input type="text" id="virCardNoIn" name="virCardNoIn" value="0000000001000000584" size="50"/></td>
    </tr>      
    <tr>
        <td>用户浏览器IP:</td>
        <td><input type="text" id="tranIP" name="tranIP" value="127.0.0.1" size="50"/></td>
    </tr>  
    <tr>
        <td>订单是否允许重复提交:</td>
        <td><input type="text" id="isRepeatSubmit" name="isRepeatSubmit" value="1" size="50"/>0:不允许,1:允许 (不填则当成1处理)</td>
    </tr>           
    <tr>
        <td>商品名称:</td>
        <td><input type="text" id="goodsName" name="goodsName" value="全聚德烤鸭一套" size="50"/></td>
    </tr>                             
            
    <tr>
        <td>商品详情:</td>
        <td><input type="text" id="goodsDetail" name="goodsDetail" value="全聚德烤鸭，肉香皮酥，好吃不贵" size="50"/></td>
    </tr>          
    <tr>
        <td>买方姓名:</td>
        <td><input type="text" id="buyerName" name="buyerName" value="张三" size="50"/></td>
    </tr>  
              
                 
    <tr>
        <td>买方联系方式:</td>
        <td><input type="text" id="buyerContact" name="buyerContact" value="010-88888888" size="50"/></td>
    </tr>         
    <tr>
        <td>商户备用信息字段:</td>
        <td><input type="text" id="merRemark1" name="merRemark1" value="" size="50"/></td>
    </tr>        
    
    <tr>
        <td>商户备用信息字段:</td>
        <td><input type="text" id="merRemark2" name="merRemark2" value="" size="50"/></td>
    </tr>      
    <tr>
        <td>银行代码:</td>
        <td><input type="text" id="bankCode" name="bankCode" value="CMB" size="50"/></td>
    </tr>          
    <tr>
        <td>用户类型:</td>
        <td><input type="text" id="userType" name="userType" value="1" size="50"/></td>
    </tr>   
    <tr><td colspan=2 width="100%"><center><input type="submit" name="submit" id="submit" value="生成md5"/></center></td></tr>

</table>
</form>