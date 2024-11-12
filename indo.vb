Public Class Form2
    ' Variabel untuk menyimpan input teks dari pengguna
    Dim inputText1, inputText2 As String

    ' Properti untuk TimerValue1, yang digunakan untuk mengakses inputText1
    Public Property TimerValue1 As String
        Get
            Return inputText1  ' Mengembalikan nilai dari inputText1
        End Get
        Set(value As String)
            inputText1 = value  ' Mengatur nilai inputText1
        End Set
    End Property

    ' Properti untuk TimerValue2, yang digunakan untuk mengakses inputText2
    Public Property TimerValue2 As String
        Get
            Return inputText2  ' Mengembalikan nilai dari inputText2
        End Get
        Set(value As String)
            inputText2 = value  ' Mengatur nilai inputText2
        End Set
    End Property

    Private Sub Label6_Click(sender As Object, e As EventArgs) Handles Label6.Click
        ' Event handler untuk Label6, tidak ada aksi yang dilakukan
    End Sub

    Private Sub Label2_Click(sender As Object, e As EventArgs)
    End Sub

    Private Sub Label3_Click(sender As Object, e As EventArgs)
    End Sub

    Private Sub RichTextBox2_TextChanged(sender As Object, e As EventArgs) Handles RichTextBox2.TextChanged
        inputText2 = RichTextBox2.Text  ' Simpan teks dari RichTextBox2 ke inputText2
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        ' Variabel untuk menyimpan nilai timer yang diambil dari input
        Dim timerValue1, timerValue2 As Integer

        ' Mencoba mengonversi kedua input teks menjadi integer
        If Integer.TryParse(inputText1, timerValue1) AndAlso Integer.TryParse(inputText2, timerValue2) Then
            ' Kirim nilai ke Form1 jika parsing berhasil
            Dim form1 As lblFoulsTeamB = DirectCast(Application.OpenForms("lblFoulsTeamB"), lblFoulsTeamB)
            form1.SetTimerValue(timerValue1, timerValue2) ' Pass both timer values
            Me.Close()  ' Tutup Form2 setelah mengirim nilai
        Else
            MessageBox.Show("Mohon masukkan angka yang valid untuk kedua timer!")  ' Tampilkan pesan jika input tidak valid
        End If
    End Sub

    Private Sub RichTextBox1_TextChanged(sender As Object, e As EventArgs) Handles RichTextBox1.TextChanged
        inputText1 = RichTextBox1.Text  ' Simpan teks dari RichTextBox1 ke inputText1
    End Sub
End Class
