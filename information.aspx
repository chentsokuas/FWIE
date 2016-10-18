<%@ Page Language="C#" AutoEventWireup="true" CodeFile="information.aspx.cs" Inherits="Test" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=zh-TW"></script>
    
</head>
<body>
    <form id="form1" runat="server">
    <div id="Info" style="width:100%;">
        <asp:Label ID="LabLat" runat="server" Text="緯度："></asp:Label><br>
        <asp:Label ID="LabLng" runat="server" Text="經度："></asp:Label><br>
        <hr />
    </div>
    <div id="data">
        
        使用一般克利金法推估之資訊<br /><br />
        <asp:Label ID="LabTemp" runat="server" Text="溫度："></asp:Label><br />
        <asp:Label ID="LabRH" runat="server" Text="濕度："></asp:Label><br />
        <asp:Label ID="LabPres" runat="server" Text="氣壓："></asp:Label><br />
        <asp:Label ID="LabDP" runat="server" Text="露點："></asp:Label><br />
        <hr />
    </div>
    </form>
</body>
</html>
