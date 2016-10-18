using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data.SqlClient;
using System.Configuration;


public partial class Test : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string Lat = "24.999";
        string Lng = "121.433";
        String cmd;
        SqlCommand scmd;
        double[,] Loc = new double[5, 2];
        String[] Station_G = new string[5];
        String[,] Station_k = new String[27, 2] 
        {
            {"板橋","466880"},
            {"淡水","466900"},
            {"鞍部","466910"},
            {"台北","466920"},
            {"竹子湖","466930"},
            {"基隆","466940"},
            {"彭佳嶼","466950"},
            {"花蓮","466990"},
            {"新屋","467050"},
            {"蘇澳","467060"},
            {"宜蘭","467080"},
            {"金門","467110"},
            {"東吉島","467300"},
            {"澎湖","467350"},
            {"台南","467410"},
            {"高雄","467440"},
            {"嘉義","467480"},
            {"台中","467490"},
            {"阿里山","467530"},
            {"大武","467540"},
            {"恆春","467590"},
            {"成功","467610"},
            {"蘭嶼","467620"},
            {"日月潭","467650"},
            {"台東","467660"},
            {"梧棲","467770"},
            {"馬祖","467990"},
        };
        double[] Temp = new double[6];
        double[] RH = new double[6];
        double[] Pres = new double[6];
        double[] DP = new double[6];
        double[,] h_kn = new double[5, 5];
        double[] h = new double[5];
        double[] G = new double[2];

        //Lat = Math.Round((Double.Parse(Request.QueryString["lat"])), 7).ToString();
        //Lng = Math.Round((Double.Parse(Request.QueryString["lng"])), 7).ToString();

        LabLat.Text += Lat;
        LabLng.Text += Lng;

        SqlConnection conn = new SqlConnection(ConfigurationManager.ConnectionStrings["RFPConnectionString"].ToString());

        /*存入GPS位置*/
        /*cmd = "INSERT INTO History (opt_GPS_Longitude,opt_GPS_Latitude) VALUES ('" + Lat + "','" + Lng +"')";
        scmd = new SqlCommand(cmd, conn);
        conn.Open();
        scmd.ExecuteNonQuery();
        conn.Close();

        /*取出資料*/
        cmd = "SELECT Top 6 * FROM [Meteorological data] where Date = '2015/1/1' AND Hour = '12' order by power(GPS_Longitude - " + Lng + ",2) + power(GPS_Latitude - " + Lat + ",2)";

        scmd = new SqlCommand(cmd, conn);
        conn.Open();
        SqlDataReader sdr = scmd.ExecuteReader();

        int l = 0;

        while (sdr.Read())
        {
            if (l > 0 && l < 6)
            {
                Loc[l - 1, 0] = double.Parse(sdr[2].ToString());
                Loc[l - 1, 1] = double.Parse(sdr[1].ToString());
                Pres[l - 1] = double.Parse(sdr[5].ToString());
                Temp[l - 1] = double.Parse(sdr[7].ToString());
                DP[l - 1] = double.Parse(sdr[8].ToString());
                RH[l - 1] = double.Parse(sdr[9].ToString());

                for (int i = 0; i < 27; i++)
                {
                    string s = sdr[0].ToString();
                    if (s.Contains(Station_k[i, 1]))
                    {
                        Station_G[l - 1] = Station_k[i, 0];
                    }
                }
            }

            l++;
        }

       


        //計算已知點間距離

        for (int i = 0; i < 5; i++)
        {
            for (int j = 0; j < 5; j++)
            {
                h_kn[i, j] = Distance(Loc[i, 0], Loc[j, 0], Loc[i, 1], Loc[j, 1]);
            }
        }

        //計算推估點與已知點間距離

        for (int i = 0; i < 5; i++)
        {
            h[i] = Distance(double.Parse(Lat), Loc[i, 0], double.Parse(Lng), Loc[i, 1]);
        }

        G = Krig(h_kn, h, Temp);
        LabTemp.Text += Math.Round(G[0], 2).ToString() + "　±　" + Math.Round(G[1], 2).ToString();

        G = Krig(h_kn, h, RH);
        LabRH.Text += Math.Round(G[0], 2).ToString() + "　±　" + Math.Round(G[1], 2).ToString();

        G = Krig(h_kn, h, Pres);
        LabPres.Text += Math.Round(G[0], 2).ToString() + "　±　" + Math.Round(G[1], 2).ToString();

        G = Krig(h_kn, h, DP);
        LabDP.Text += Math.Round(G[0], 2).ToString() + "　±　" + Math.Round(G[1], 2).ToString();
    }

    double Distance(double x1, double x2, double y1, double y2)
    {
        double h = 0;

        h = Math.Pow((Math.Pow((x1 - x2), 2) + Math.Pow((y1 - y2), 2)), 0.5);

        return h;
    }

    double CovLinear(double h, double c0, double x)
    {
        return (c0 + h * x);
    }

    void MInverse(double[,] Src, int dim, double[,] des)
    {
        double temp;

        for (int i = 0; i < dim; i++)
        {
            for (int j = 0; j < dim; j++)
            {
                if (i == j)
                {
                    des[i, j] = 1;
                }
                else { des[i, j] = 0; }
            }
        }

        for (int k = 0; k < dim; k++)
        {
            if (Src[k, k] != 1)
            {
                temp = Src[k, k];
                for (int i = 0; i < dim; i++)
                {
                    Src[k, i] = Src[k, i] / temp;
                    des[k, i] = des[k, i] / temp;
                }
            }

            for (int i = 0; i < dim; i++)
            {
                if (Src[i, k] != 0 && i != k)
                {
                    temp = Src[i, k];
                    for (int j = 0; j < dim; j++)
                    {
                        Src[i, j] = Src[i, j] - (Src[k, j] * temp);
                        des[i, j] = des[i, j] - (des[k, j] * temp);
                    }
                }
            }
        }
    }

    double[] Krig(double[,] Distance_K, double[] Distance_Unk, double[] Measur)
    {
        double[,] Covh_kn = new double[6, 6];
        double[] Covh = new double[6];
        double[,] res = new double[6, 6];
        double[] w = new double[6];
        double[] G = new double[2];

        /*轉換已知距離Cov陣列*/
        for (int i = 0; i < 6; i++)
        {
            for (int j = 0; j < 6; j++)
            {
                if (i != 5 && j != 5)
                {
                    Covh_kn[i, j] = CovLinear(Distance_K[i, j], -3.146, 15.928);
                }
                else
                {
                    if (i == 5)
                    {
                        Covh_kn[i, j] = 1;
                    }
                    else { Covh_kn[i, j] = 1; }

                }

            }
        }
        Covh_kn[5, 5] = 0;

        /*轉換推估點Cov*/
        for (int i = 0; i < 5; i++)
        {
            Covh[i] = CovLinear(Distance_Unk[i], 6.214, 5.6912);
        }
        Covh[5] = 1;

        MInverse(Covh_kn, 6, res);

        /*計算權重*/
        for (int i = 0; i < 6; i++)
        {
            for (int j = 0; j < 6; j++)
            {
                w[i] += Covh[j] * res[j, i];
            }
        }

        /*計算推估值*/
        for (int i = 0; i < 6; i++)
        {
            G[0] += Measur[i] * w[i];
            G[1] += w[i] * Covh[i];
        }

        G[1] = Math.Pow(Math.Abs(G[1]), 0.5);

        return G;
    }
}