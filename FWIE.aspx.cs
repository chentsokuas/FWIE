using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Collections;
using System.Data.SqlClient;
using System.Configuration;

// 引用 AForge 的類神經網路函式庫
using AForge;
using AForge.Neuro;
using AForge.Neuro.Learning;


public partial class FWIE : System.Web.UI.Page
{

    protected void Page_Load(object sender, EventArgs e)
    {
       
    }
    protected void ButTemp_Click(object sender, EventArgs e)
    {
        string strJS = "";
        double dis = double.Parse(grid_weight.Text) * 0.01;

        ArrayList allRectangle = GetData("溫度", dis);

        strJS = @"var sites = [" + String.Join(",", allRectangle.ToArray()) + "];DataRectangle(sites," + dis / 2 + ",'溫度：'" + ");";

        ScriptManager.RegisterStartupScript(this.Page, this.Page.GetType(), "alert", strJS, true);
    }
   
    private ArrayList GetData(string p,double dis)
    {     
        grid_Num.Text = "";
        
        ArrayList Data = new ArrayList();
        Double Lat = 25.29978375551047 - (dis / 2), lng = 120.03322005271912 + (dis / 2);
        int Count = 0;

        double[] G = new double[2];
        double[] Temp = new double[29];
        double[,] h_kn = new double[28, 28];
       
        double[] h = new double[28];
        double[,] Loc = new double[28, 2];
        
                
        SqlConnection conn = new SqlConnection(ConfigurationManager.ConnectionStrings["RFPConnectionString"].ToString());
        
        try
        {
            String cmd;
            SqlCommand scmd;

            cmd = "SELECT  * FROM [Meteorological data] where Date LIKE '%2015/1/1%' AND Hour = '12' order by power(GPS_Longitude - " + lng + ",2) + power(GPS_Latitude - " + Lat + ",2)";

            scmd = new SqlCommand(cmd, conn);
            conn.Open();
            SqlDataReader sdr = scmd.ExecuteReader();

            int l = 0;
            int x = 0 ;

            switch (p) 
            {
                case "溫度":
                    x = 7;
                    break;
                case "露點":
                    x = 8;
                    break;
                case "濕度":
                    x = 9;
                    break;
            }
            
            while (sdr.Read())
            {
                Loc[l, 0] = double.Parse(sdr[2].ToString());
                Loc[l, 1] = double.Parse(sdr[1].ToString());
                Temp[l] = double.Parse(sdr[x].ToString()); 
                /*if (p == "溫度")
                {
                    Temp[l] = double.Parse(sdr[7].ToString());
                }
                else if (p == "露點") 
                {
                    Temp[l] = double.Parse(sdr[8].ToString());
                }
                else if (p == "濕度")
                {
                    Temp[l] = double.Parse(sdr[9].ToString());
                }*/
                l++;
            }
            //計算已知點間距離
            for (int i = 0; i < l; i++)
            {
                for (int j = 0; j < l; j++)
                {
                    h_kn[i, j] = Distance(Loc[i, 0], Loc[j, 0], Loc[i, 1], Loc[j, 1]);
                }
            }
        }
        
        catch (Exception e)
        {
            //labMistake.Text = e.Message;
            //labMistake.Visible = true;
        }
        finally 
        {
            conn.Close();
            conn.Dispose();
        }

        while (lng < 122.007497549057)
        {
            double LocTmp = Lat;
            while (Lat > 21.893677067596503)
            {
                
                //計算推估點與已知點間距離

                for (int i = 0; i < 28; i++)
                {
                    h[i] = Distance(Lat, Loc[i, 0], lng, Loc[i, 1]);
                }
                
                G = Krig(h_kn, h, Temp);

                /*double Tmp =  360 * (G[0] / 40);
                int Color = 360 - Convert.ToInt32(Math.Floor(Tmp));

                Data.Add("[" + Lat.ToString() + ", " + lng.ToString() + ", "+ Color.ToString() + "]");*/

                Data.Add("[" + Lat.ToString() + ", " + lng.ToString() + ", " + Math.Floor(G[0]).ToString() + "]");
            
                Lat -= dis;
                Count++;
            }
            Lat = LocTmp;
            lng += dis;
        }
        grid_Num.Text = Count.ToString();
        return Data;
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
        double[,] Covh_kn = new double[29, 29];
        double[] Covh = new double[29];
        double[,] res = new double[29, 29];
        double[] w = new double[29];
        double[] G = new double[2];

        /*轉換已知距離Cov陣列*/
        for (int i = 0; i < 29; i++)
        {
            for (int j = 0; j < 29; j++)
            {
                if (i != 28 && j != 28)
                {
                    Covh_kn[i, j] = CovLinear(Distance_K[i, j], 6.214, 5.6912);
                }
                else
                {
                    if (i == 28)
                    {
                        Covh_kn[i, j] = 1;
                    }
                    else { Covh_kn[i, j] = 1; }

                }

            }
        }
        Covh_kn[28, 28] = 0;

        /*轉換推估點Cov*/
        for (int i = 0; i < 28; i++)
        {
            Covh[i] = CovLinear(Distance_Unk[i], 6.214, 5.6912);
        }
        Covh[28] = 1;

        MInverse(Covh_kn, 29, res);

        /*計算權重*/
        for (int i = 0; i < 29; i++)
        {
            for (int j = 0; j < 29; j++)
            {
                w[i] += Measur[j] * res[j, i];
            }
        }

        /*計算推估值*/
        for (int i = 0; i < 29; i++)
        {
            G[0] += Covh[i] * w[i];
            G[1] += w[i] * Covh[i];
        }

        G[1] = Math.Pow(Math.Abs(G[1]), 0.5);

        return G;
    }

    
    protected void ButSOM_Click(object sender, EventArgs e)
    {
        string strJS = "";
 
        double dis = double.Parse(grid_weight.Text) * 0.01;
    
        ArrayList allRectangle = GetData("溫度", dis);
        ArrayList somRectangle = new ArrayList();
        //SOM 副程式



    /*    for (int i = 0; i < allRectangle.Count; i++)
        {
          
            somRectangle.Add(som_temp[i]);
        }*/
        strJS = @"var sites = [" + String.Join(",", allRectangle.ToArray()) +
            @"];DataRectanglesom(sites," + dis / 2 + ",'溫度：'" + ");";
        ScriptManager.RegisterStartupScript(this.Page, this.Page.GetType(), "alert", strJS, true);


    }

    
  
    /*public class som 
    {
        private Neuron[,] outputs;  // Collection of weights.
        private int iteration;      // Current iteration.
        private int length;        // Side length of output grid.
        private int dimensions;    // Number of input dimensions.
        private Random rnd = new Random();
 
        private List<string> labels = new List<string>();
        private List<double[]> patterns = new List<double[]>();
 
        static void Main(string[] args)
        {
            new Map(3, 10, "Food.csv");
            Console.ReadLine();
        }
 
        public Map(int dimensions, int length, string file)
        {
            this.length = length;
            this.dimensions = dimensions;
            Initialise();
            LoadData(file);
            NormalisePatterns();
            Train(0.0000001);
            DumpCoordinates();
        }
 
        private void Initialise()
        {
            outputs = new Neuron[length, length];
            for (int i = 0; i < length; i++)
            {
                for (int j = 0; j < length; j++)
                {
                    outputs[i, j] = new Neuron(i, j, length);
                    outputs[i, j].Weights = new double[dimensions];
                    for (int k = 0; k < dimensions; k++)
                    {
                        outputs[i, j].Weights[k] = rnd.NextDouble();
                    }
                }
            }
        }
 
        private void LoadData(string file)
        {
            StreamReader reader = File.OpenText(file);
            reader.ReadLine(); // Ignore first line.
            while (!reader.EndOfStream)
            {
                string[] line = reader.ReadLine().Split(',');
                labels.Add(line[0]);
                double[] inputs = new double[dimensions];
                for (int i = 0; i < dimensions; i++)
                {
                    inputs[i] = double.Parse(line[i + 1]);
                }
                patterns.Add(inputs);
            }
            reader.Close();
        }
 
        private void NormalisePatterns()
        {
            for (int j = 0; j < dimensions; j++)
            {
                double sum = 0;
                for (int i = 0; i < patterns.Count; i++)
                {
                    sum += patterns[i][j];
                }
                double average = sum / patterns.Count;
                for (int i = 0; i < patterns.Count; i++)
                {
                    patterns[i][j] = patterns[i][j] / average;
                }
            }
        }
 
        private void Train(double maxError)
        {
            double currentError = double.MaxValue;
            while (currentError > maxError)
            {
                currentError = 0;
                List<double[]> TrainingSet = new List<double[]>();
                foreach (double[] pattern in patterns)
                {
                    TrainingSet.Add(pattern);
                }
                for (int i = 0; i < patterns.Count; i++)
                {
                    double[] pattern = TrainingSet[rnd.Next(patterns.Count - i)];
                    currentError += TrainPattern(pattern);
                    TrainingSet.Remove(pattern);
                }
                Console.WriteLine(currentError.ToString("0.0000000"));
            }
        }
 
        private double TrainPattern(double[] pattern)
        {
            double error = 0;
            Neuron winner = Winner(pattern);
            for (int i = 0; i < length; i++)
            {
                for (int j = 0; j < length; j++)
                {
                    error += outputs[i, j].UpdateWeights(pattern, winner, iteration);
                }
            }
            iteration++;
            return Math.Abs(error / (length * length));
        }
 
        private void DumpCoordinates()
        {
            for (int i = 0; i < patterns.Count; i++)
            {
                Neuron n = Winner(patterns[i]);
                Console.WriteLine("{0},{1},{2}", labels[i], n.X, n.Y);
            }
        }
 
        private Neuron Winner(double[] pattern)
        {
            Neuron winner = null;
            double min = double.MaxValue;
            for (int i = 0; i < length; i++)
                for (int j = 0; j < length; j++)
                {
                    double d = Distance(pattern, outputs[i, j].Weights);
                    if (d < min)
                    {
                        min = d;
                        winner = outputs[i, j];
                    }
                }
            return winner;
        }
 
        private double Distance(double[] vector1, double[] vector2)
        {
            double value = 0;
            for (int i = 0; i < vector1.Length; i++)
            {
                value += Math.Pow((vector1[i] - vector2[i]), 2);
            }
            return Math.Sqrt(value);
        }


    }
    public class Neuron
    {
        public double[] Weights;
        public int X;
        public int Y;
        private int length;
        private double nf;

        public Neuron(int x, int y, int length)
        {
            X = x;
            Y = y;
            this.length = length;
            nf = 1000 / Math.Log(length);
        }

        private double Gauss(Neuron win, int it)
        {
            double distance = Math.Sqrt(Math.Pow(win.X - X, 2) + Math.Pow(win.Y - Y, 2));
            return Math.Exp(-Math.Pow(distance, 2) / (Math.Pow(Strength(it), 2)));
        }

        private double LearningRate(int it)
        {
            return Math.Exp(-it / 1000) * 0.1;
        }

        private double Strength(int it)
        {
            return Math.Exp(-it / nf) * length;
        }

        public double UpdateWeights(double[] pattern, Neuron winner, int it)
        {
            double sum = 0;
            for (int i = 0; i < Weights.Length; i++)
            {
                double delta = LearningRate(it) * Gauss(winner, it) * (pattern[i] - Weights[i]);
                Weights[i] += delta;
                sum += delta;
            }
            return sum / Weights.Length;
        }*/




   
}
