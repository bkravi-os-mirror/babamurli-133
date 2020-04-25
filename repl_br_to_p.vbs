'===========================================================================================
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\00. Main"
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\00. Main\01. ADHD Awareness Month\00. Main"
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\03. Eating Disorders\00. Main"
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\01. Addiction\00. Main"
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\01. Addiction\01. Alcohol Use\00. Main"
'Const workingDir = "D:\New Work\x-verywellmind\01. Disorders\01. Addiction\02. Addictive Behaviors\00. Main"
Const workingDir = "D:\php projects\xampp\htdocs\x-verywellmind\03. Psychology\07. Emotions"
'Const workingDir = "D:\New Work\x-verywellmind\00. Coronavirus"
'===========================================================================================

Const ForReading = 1
Const ForWriting = 2
Const one = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>"
Const two = "<title>Brahmakumaris BK DR Luhar</title></head>"
Const three = "<body bgcolor='#ffebcc'>"
Const four = "<blockquote><blockquote><hr><p align='center' dir='ltr'><font face='Arial' color='#FF00FF' size='5'>Disorders - </font>"
Const five = "<font face='Arial' color='#000080' size='5'>Addiction - </font>"
Const six = "<font face='Arial' color='#008000' size='5'>"
Const fourthlast = "</font></p><hr><font face='Arial' style='font-size: 16pt' color='#000080'><p align='justify' dir='ltr'>Test"
Const thirdlast = "</p></font>"
Const secondlast = "<font face='Arial' style='font-size: 16pt' color='#000080' color='#000080'>"
Const last = "<hr></font></blockquote></blockquote></body></html>"
Set objFSO = CreateObject("Scripting.FileSystemObject")

Function GetRecentFile(path)
  Dim fso, file
  Set fso = CreateObject("Scripting.FileSystemObject")
  Set GetRecentFile = Nothing
  For Each file in fso.GetFolder(path).Files
    If GetRecentFile is Nothing Then
      Set GetRecentFile = file
    ElseIf file.DateLastModified > GetRecentFile.DateLastModified Then
      Set GetRecentFile = file
    End If
  Next
End Function

Function countString(inputString, stringToBeSearchedInsideTheInputString, ByRef count)
    count = (Len(inputString) - Len(Replace(inputString, stringToBeSearchedInsideTheInputString, ""))) / Len(stringToBeSearchedInsideTheInputString)
End Function

'Now commenting below file creation. As Now I am using HTM generator to create the file-------------
''dim result
''result = msgbox("Want to create New FILE??", 4 , "CREATE NEW FILE??")
'---------------------------------------------------------------------------------------------------
result = 0 'To by pass below condition

If result = 6 Then
	strFileName = GetRecentFile(workingDir)
	onlyFileName = Mid(strFileName, 1+InstrRev(strFileName, "\"))
	tmp = Split(onlyFileName, ".")
	file_prefix = tmp(0) + 1   'converting into integer
	If(file_prefix >= 0 and file_prefix <= 9) Then
		file_prefix = "00" & file_prefix
	ElseIf(file_prefix >= 10 and file_prefix <= 99) Then
		file_prefix = "0" & file_prefix
	End If

	dim fileName, title
	fileName = InputBox("Enter File Name:")
	fileName = file_prefix & ". " & fileName & ".htm"
	title = InputBox("Enter Title:")
	fullFileName = Mid(strFileName, 1, InstrRev(strFileName, "\")) & fileName

	Set objFileWrite = objFSO.CreateTextFile(fullFileName, true)
	objFileWrite.WriteLine one
	objFileWrite.WriteLine two
	objFileWrite.WriteLine three
	objFileWrite.WriteLine four
	objFileWrite.WriteLine five
	objFileWrite.WriteLine six
	objFileWrite.WriteLine title
	objFileWrite.WriteLine fourthlast
	objFileWrite.WriteLine thirdlast
	objFileWrite.WriteLine secondlast
	objFileWrite.WriteLine last
	objFileWrite.Close

	MsgBox("File " & fileName & " created with Title as '" & title & "'")
	'WScript.Quit 1
Else
	strFileName = GetRecentFile(workingDir)
	Set objFileRead = objFSO.OpenTextFile(strFileName, ForReading)

	strFullFileContent = objFileRead.ReadAll
	lenBefore = Len(strFullFileContent)
	objFileRead.Close

	three_br = 0
	two_br = 0
	one_br = 0
	specialCharCounter = 0

	'font and color of entire body ---------------------------------------------------
	strFullFileContent = Replace(strFullFileContent, "<font face='Arial' style='font-size: 16pt'", "<font face='Arial' style='font-size: 16pt' color='#000080'")
	'----------------------------------------------------------------------------------

	'Special Character----------------------------------------------------------------
	Call countString(strFullFileContent, "#65279;", specialCharCounter)

	If(specialCharCounter > 0) Then
		For i = 0 To 19
			counter = 20 - i
			strFullFileContent = Replace(strFullFileContent, counter & "&#65279;", "")
		Next
	End if
	'----------------------------------------------------------------------------------

	'<br>s-----------------------------------------------------------------------------
	'Call countString(strFullFileContent, "<br>\n<br>\n<br>", three_br)
	Call countString(strFullFileContent, "<br>" & vbLf & "<br>" & vbLf & "<br>", three_br)
	strFullFileContent = Replace(strFullFileContent, "<br>" & vbLf & "<br>" & vbLf & "<br>", "</p><p align='justify' dir='ltr'>")

	'Call countString(strFullFileContent, "<br>\n<br>", two_br)
	Call countString(strFullFileContent, "<br>" & vbLf & "<br>", two_br)
	strFullFileContent = Replace(strFullFileContent, "<br>" & vbLf & "<br>", "</p><p align='justify' dir='ltr'>")

	Call countString(strFullFileContent, "<br>", one_br)
	strFullFileContent = Replace(strFullFileContent, "<br>", "</p><p align='justify' dir='ltr'>")
	'----------------------------------------------------------------------------------

	If((three_br + two_br + one_br + specialCharCounter) > 0) Then
		Set objFileWrite = objFSO.OpenTextFile(strFileName, ForWriting)
		objFileWrite.Write strFullFileContent  'WriteLine adds extra CR/LF
		objFileWrite.Close
		res = MsgBox("File: " & strFileName & vbNewLine & vbNewLine & "3 <br>: " & three_br & vbNewLine & "2 <br>: " & two_br & vbNewLine & "1 <br>: " & one_br & vbNewLine & "Special Char: " & specialCharCounter, 0, "Message")
	Else
		res = MsgBox("NO <br>  found in File: " & strFileName, 0, "Message")
	End if
End If
'WScript.Quit 1