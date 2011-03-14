/*
gcc gk4.c -o gk4 -mwindows -Wl,-subsystem,console -lcomctl32 
gk4.c 
the main gui part with comctl
CoInitializeEx 

*/
#define _WIN32_IE 0x0400


#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#include <commctrl.h>
#include <richedit.h>
#include <tlhelp32.h>

#ifndef LVS_EX_FULLROWSELECT
#define LVS_EX_FULLROWSELECT  0x00000020
#endif
#ifndef LVS_EX_GRIDLINES
#define LVS_EX_GRIDLINES        0x00000001
#endif
#ifndef LV_VIEW_LIST
#define LV_VIEW_LIST 0x00000003
#endif

#define IDC_LIST 1234
#define ID_RICH_EDIT 1235
#define ID_BUTTON1 1236 /// for close button
#define ID_CHECK1 1237  /// for the check box 

#define __strtok_r strtok_r
# define __rawmemchr strchr
#define MSGLEN 8192
#define WAIT_TIME 1000*4
//curl -s -d "action=vipnews&DiskId=001916005802&D_msgtime=2011-03-26 12:33:13" -k https://127.0.0.1/whsoft/WHSoft/DLL/SoftFind.asp
//char self[1024];

char * asp_path = "http://127.0.0.1/whsoft/WHSoft/DLL/SoftFind.asp";
char Buf[MSGLEN];
char time_buf[21];
char day_buf[11];
// 001 916 005 802
char diskid[13];
int unix_time1;
int unix_time2;


typedef struct
{
   char  szItemNr[512]; // about 256 chinese words
}Item;

typedef struct 
{
	char name[25];
	char content[4096];

}myIterm;

LRESULT CALLBACK WndProc(HWND, UINT, WPARAM, LPARAM);
LRESULT CALLBACK EnumFunc(HWND hwnd,LPARAM lParam);

int deinit();
int init();

static char szAppName[] = "GoldKey_Window";

HINSTANCE hInst;
HWND hwnd; // the core window
HWND hList; // the left list
HWND hwndEdit;
HWND hwndTab;
HWND button;
HWND hCheck;
static int index; // for the List using
Item ListItem[1];
myIterm mterm[100]; // only list 100 items 
int myIndex; /// for myIterm count
LV_ITEM lv;
LV_COLUMN lvC;
LV_DISPINFO *lvd;
NMHDR *hdr;

CHARFORMAT cf;
CREATESTRUCT  *cs;

int if_quit;
int wait_time = 1000*60*30;// half an hour
char current_dir[1024];
char current_exe_name[256];

typedef void (*pfunc)();
typedef void (*pfunc2)();
pfunc2  hkprcSysMsg; 
pfunc mt4_func;
static   HINSTANCE   hinstDLL;  
static   HHOOK   hhookSysMsg;   


int do_hook()
{
	hinstDLL   =   LoadLibrary((LPCTSTR)"test3.dll");   
	if( hinstDLL ==NULL)
	{
    		MessageBox(NULL,"LoadLibrary error ","Info",IDOK);
    		printf("%d\n,",GetLastError());
    		return -1;			
	}
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "SetHooked");
	if( hkprcSysMsg ==NULL) 
	{
    		MessageBox(NULL,"GetProcAddress1 error ","Info",IDOK);
    		printf("%d\n,",GetLastError());
    		return -1;		
	}

	mt4_func = (pfunc)GetProcAddress(hinstDLL, "GetHooked");
	if( mt4_func ==NULL) 
	{
    		MessageBox(NULL,"GetProcAddress2 error ","Info",IDOK);
		printf("%d\n,",GetLastError());
		return -1;		
	}
	mt4_func();	
	hkprcSysMsg();
	return 0;
}

int do_unhook()
{
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "unSetHooked");
	if( hkprcSysMsg ==NULL) 
	{
	    	MessageBox(NULL,"GetProcAddress3 error ","Info",IDOK);
    		printf("%d\n,",GetLastError());
	    	return -1;		
	} 
	mt4_func = (pfunc)GetProcAddress(hinstDLL, "GetUnHooked");
	if( mt4_func ==NULL) 
	{
    		MessageBox(NULL,"GetProcAddress4 error ","Info",IDOK);
	    	printf("%d\n,",GetLastError());
    		return -1;		
	}

	hkprcSysMsg();
	mt4_func();
	return 0;
}

int ReadFromPipeNoWait( HANDLE hPipe, char* pDest, int nMax )
{
    DWORD nBytesRead= 0;
    DWORD nAvailBytes;
    char cTmp;
    memset( pDest, 0, nMax );
 
    PeekNamedPipe( hPipe, &cTmp, 1, NULL, &nAvailBytes, NULL );
    if ( nAvailBytes == 0 || nBytesRead >=1023 ) {
         return( nBytesRead );
    }
  
    ReadFile( hPipe, pDest, nMax-1, &nBytesRead, NULL); 
    return( nBytesRead );
}

BOOL ExecAndProcessOutput(LPCSTR szCmd, LPCSTR szParms  )
{
    SECURITY_ATTRIBUTES rSA=    {0};
    rSA.nLength=              sizeof(SECURITY_ATTRIBUTES);
    rSA.bInheritHandle=       TRUE;
	char sCmd[1024];
    HANDLE hReadPipe, hWritePipe;
    CreatePipe( &hReadPipe, &hWritePipe, &rSA, 25000 );

    PROCESS_INFORMATION rPI= {0};
    STARTUPINFO         rSI= {0};
    rSI.cb=             sizeof(rSI);
    rSI.dwFlags=     STARTF_USESHOWWINDOW |STARTF_USESTDHANDLES;
    rSI.wShowWindow= SW_HIDE;  // or SW_SHOWNORMAL or SW_MINIMIZE
    rSI.hStdOutput=  hWritePipe;
    rSI.hStdError=   hWritePipe;

	sprintf(sCmd,"\"%s\" %s", szCmd,szParms);
   
    BOOL fRet=CreateProcess(NULL,(LPSTR)(LPCSTR)sCmd, NULL,
              NULL,TRUE,0,0,0, &rSI, &rPI );
    if ( !fRet ) {
         return( FALSE );
    }
   
   char dest[1000];
   
   DWORD dwRetFromWait= WAIT_TIMEOUT;
   while ( dwRetFromWait != WAIT_OBJECT_0 ) {
        dwRetFromWait= WaitForSingleObject( rPI.hProcess, 100 );
        if ( dwRetFromWait == WAIT_ABANDONED ) {
            break;
        }
        while ( ReadFromPipeNoWait( hReadPipe, dest, sizeof(dest) ) > 0 ) {
			strcat(Buf,dest);
        }
    }

    CloseHandle( hReadPipe  );
    CloseHandle( hWritePipe );
    CloseHandle( rPI.hThread); 
    CloseHandle( rPI.hProcess);

    return TRUE;
}

char *replace(char *st, char *orig, char *repl) {
  static char buffer[4096];
  char *ch;
  if (!(ch = strstr(st, orig)))
     return st;
    strncpy(buffer, st, ch-st);  
    buffer[ch-st] = 0;
    sprintf(buffer+(ch-st), "%s%s", repl, ch+strlen(orig));
    return buffer;
}

void clean_ascii(char*str)
{
	int i;
	
	i = 0;
	if( strlen(str)>1)
	{
		for(i=0;i<strlen(str);i++)
		{
			if( (int)str[i] < 32 || (int)str[i] >126)
			{
				str[i]='\0';return;
			}
		}
	}
}

int natoi( char *num)
{
	int minus = 0;
	if( num[minus]=='-')
	{
		minus++;
		num+=minus;
		return 0 - atoi(num);
	}else
	{
		return atoi(num);
	}
}

int run_cmd(char*cmd,char*path)
{

	int a;
	a = ExecAndProcessOutput("curl.exe",cmd);

	return a;
}

int file_exists( char * filename)
{
	FILE*file;
    if ( (file = fopen(filename, "r")))
    {
        fclose(file);
        return 1;
    }
    return 0;
}

int get_now_time()
{
        time_t lt;
        struct tm*timeinfo;
        char buffer[21];
        lt = time(NULL);
        printf(ctime(&lt));
        timeinfo = localtime(&lt);
        strftime(buffer,21,"%Y-%m-%d %X",timeinfo);
        printf("now time is %s\n",buffer);
        strcpy(time_buf,buffer);
        return 0;
}

int get_now_day()
{
        time_t lt;
        struct tm*timeinfo;
        char buffer[14];
        lt = time(NULL);
        printf(ctime(&lt));
        timeinfo = localtime(&lt);
        strftime(buffer,14,"%Y-%m-%d",timeinfo);
        printf("now Day is %s\n",buffer);
        strcpy(day_buf,buffer);
        return 0;
}

char *
__strtok_r (char *s, const char *delim, char **save_ptr)
{
  char *token;

  if (s == NULL)
    s = *save_ptr;


  s += strspn (s, delim);
  if (*s == '\0')
    {
      *save_ptr = s;
      return NULL;
    }

  token = s;
  s = strpbrk (token, delim);
  if (s == NULL)

    *save_ptr = __rawmemchr (token, '\0');
  else
    {

      *s = '\0';
      *save_ptr = s + 1;
    }
  return token;
}

char *get_filename(char*f)
{
	char*pch;
	char *saveptr;
	saveptr = NULL;
	if(strchr(f,'.'))
	{
		pch = strtok_r(f,".",&saveptr);
		if(pch != NULL)
			return pch;
	}else return f;
}

char *get_ext(char*f)
{
	char *pch;
	char *saveptr;
	saveptr = NULL;
	if(strchr(f,'.'))
	{
		pch = strtok_r(f,".",&saveptr);
		if(pch != NULL)
		pch = strtok_r(NULL,".", &saveptr);
		if(pch != NULL)
			return pch;
	}else return f;
}

char *get_last_name(char*f)
{

	char buf[1024];
	strcpy(buf,f);
	char*pch;
	char*pch2;
	if( strchr(buf,'\\') !=NULL)
	{
		pch = strtok(buf,"\\");
		while( pch!=NULL)
		{
			//printf("%s\n",pch);
			pch2 = pch;
			pch = strtok(NULL,"\\");
			if( pch == NULL) break;
		}
		return pch2;
	}
	else 
	return NULL;
}
char* get_dir(char*f)
{
//	printf("char*f = %s\n",f);
	char*pch;
	char *pch2;
	char *fpath;
	char buf[128];
	char buf2[1024];
	char *pt;
	
	strcpy(buf2,f);
	pt = get_last_name(buf2);

	fpath = (char*)malloc( 1024* sizeof(char));
	memset(fpath,0,1024);

	if( strchr(buf2,'\\')!=NULL)
	{
		pch = strtok(buf2,"\\");
		while( pch !=NULL)
		{
			pch2 = pch;
		//	printf("pch2: %s\n", pch2);
			if( stricmp(pt, pch2)!=0)
			{
				sprintf(buf,"%s\\", pch2);
				strcat( fpath, buf);
				memset(buf,0,128);
			}
		//	printf("%s\n",fpath);	
			pch = strtok(NULL,"\\");
			if( pch == NULL) break;
			 
		}
		return fpath;
	}
	else {
		printf(" strchr(f ,\\) falied\n");
		return NULL;
	}
}

int read_hcwt(char*fname)
{
	FILE *fp;
	char*name;
	char*pt;
	char str_buf[4096];

	memset(str_buf,0,4096);
	
	fp = fopen(fname,"r");
	if(fp !=NULL)
	{
		fread(str_buf,4096,1, fp);
		fclose(fp);
	}
	name = get_filename(fname);
	pt = replace(replace(replace(name,"_"," "),"#",":"),"#",":");

	printf("name = %s\n content=%s\n",pt,str_buf);
	
	myIndex++;
	if( myIndex < 100)
	{
		strcpy(mterm[myIndex].name, name);
		strcpy(mterm[myIndex].content, str_buf);
		AdjustListView(hList, &lv, myIndex);		
	}
	return 0;
}

int scan_hcwt(char*dir)
{
	char fpath[1024];
	HANDLE hFind;
	WIN32_FIND_DATA FindFileData;
	memset(fpath,0,1024);
	sprintf(fpath,"%s/*.hcwt",dir);
	myIndex = 0;
	if((hFind = FindFirstFile(fpath, &FindFileData)) != INVALID_HANDLE_VALUE)
	{
		do{
			printf("%s\n", FindFileData.cFileName);
			read_hcwt(FindFileData.cFileName);
		}while(FindNextFile(hFind, &FindFileData));
		FindClose(hFind);
	}else
	{
		printf("no hcwt found\n");
	}
}


int parse_vipmsg(char*msg)
{
	// msg is something like  1|2011-03-26 08:38:02|vipmsg|第一个|1|allVIP,1,7,8
	char *pch;
	char *fname;
	char *content;
	char *pt;
	FILE *fp;
	char fname_buf[35];
	char msg2[MSGLEN];
	fname =NULL; content = NULL;
	fp = NULL;
	memset(msg2,0,MSGLEN);
	memcpy(msg2,msg,strlen(msg));
	if(strchr(msg2,'|'))
	{
	
		pch = strtok(msg2,"|");
		pch = strtok(NULL,"|");
		if( pch !=NULL)
			fname = pch;
		pch = strtok(NULL,"|");
		if(pch != NULL)
		pch = strtok(NULL,"|");
		if(pch != NULL)
			content = pch;
			
	}

	if( fname!=NULL && content !=NULL)
	{
		sprintf(fname_buf,"%s.hcwt",fname);
		pt = replace(replace(replace(fname_buf," ","_"),":","#"),":","#");
		
		printf("pt = %s\n",pt);
		if( file_exists( pt ) == 0)
		{
//			sprintf(fname_buf,"%s.hcwt",pt);
		
		//	printf("fname_buf = %s\n", replace(replace(fname_buf," ","_"),":","#"));
		
			fp = fopen(pt, "w" );
			if(fp!=NULL)
			{
				fwrite(content,1, strlen(content),fp);
				
				fclose(fp);
				return 0;
			}else {printf("fopen orror\n"); return -1;}

			scan_hcwt(current_dir);
			ShowWindow(hwnd,1);
			
		}
		
	}

	
}


void write_config()
{
	FILE*fp;
	fp =fopen("config","w");
	if(fp)
	{
		fwrite(day_buf,1, strlen( day_buf), fp);
		fclose(fp);
		return ;
	}
}

void del_config()
{
	remove("config");
}

int check_config()
{
	FILE *fp;
	char buf[11];
	memset(buf,0,11);
	fp =fopen("config","r");
	if(fp)
	{
		fgets(buf,11,fp);
		fclose(fp);
		if(strcmp( day_buf, buf) != 0) // 日期不对了，里面的日期过期了 
		{
			if( remove( "config" ) != 0 )
			{
				printf("remove confige error");
				return -1;
			} else return 0;
		}else return 1;
	}else return -2;// maybe config not exsits
	
}

void check_loop()
{

	int a;
	DWORD i , nBufferLength = 1024;
    	char  dbuffer[1024];
	char cmd_line[1024];
	char*pch;
	char sep[2];
	char *saveptr1;

	memset(cmd_line,0,1024);
	
	a  = 0;
	sprintf(cmd_line, " -s -d \"action=vipnews&DiskId=%s&D_msgtime=2011-03-26 12:33:13\"  %s",diskid,asp_path);
//	sprintf(cmd_line, " -s -d \"action=vipnews&DiskId=%s&D_msgtime=%s\"  %s",diskid,time_buf,asp_path);

	sep[0] = (char)28;
	sep[1] = '\0';



	if( file_exists("curl.exe") == 1 && file_exists("config") == 0 )
	{
		if( if_quit == 0)
		{
			// code here
			memset(Buf,0,MSGLEN);
			a = run_cmd(cmd_line,"");
		
			if(a)
			{
				if(strstr(Buf, sep))
				{
				//	printf("Buf %s\n",Buf);
					pch = strtok_r(Buf,sep,&saveptr1);
					i = 0;
					while(pch!=NULL)
					{
						
						parse_vipmsg( pch);
											
						pch = strtok_r(NULL,sep,&saveptr1);
						if(pch == NULL) break;
					}
				//	scan_hcwt(current_dir);
				}
				
			}
//			Sleep(WAIT_TIME);
		
		}
	}else
	{
		printf("no curl.exe found\n");
    		i = GetCurrentDirectory(nBufferLength , dbuffer) ;
		MessageBox(NULL,dbuffer,"error",MB_OK);
		return ;		
	}

	return ;
}

int ck()
{
	char cmd_line[2048];
	char self[256];
//	sprintf(self,"%s", argv[0]);
	memset(self,0,256);
	memset(cmd_line,0,2048);
	char*pt;
	GetModuleFileName(NULL,(LPSTR)&self,256);
//	printf("self = %s\n", self);
	pt = get_last_name(self);
//	printf("-- %s\n",pt);

	pt = get_dir(self);
//	printf("-- %s\n", pt);
	chdir(pt);
	//SetCurrentDirectory(pt); <-- this is dir
	scan_hcwt(pt);
	

}

void SetDefaultFont (HWND hwnd)
{
	SendMessage(hwnd, WM_SETFONT, (WPARAM)GetStockObject(DEFAULT_GUI_FONT), (LPARAM)TRUE);
}

void create_btns(HWND parent)
{
	//[ ]  今日不再提示已发布的消息  关闭
	RECT rc = {0};
	GetWindowRect(hwnd,&rc);
	button = CreateWindow("Button","关闭",
		BS_PUSHBUTTON | WS_CHILD | WS_VISIBLE ,rc.right-rc.left-70 ,rc.bottom-rc.top-55,50,25,parent,(HMENU)ID_BUTTON1,hInst,0);	
	if(button == NULL)
	{
		MessageBox(NULL, "Create BUTTON Failed!", szAppName, MB_OK);
		return ;
	}
	SetDefaultFont(button);
	//BS_CHECKBOX 
	hCheck =  CreateWindow("Button"," 今日不再提示已发布过的消息",
		BS_AUTOCHECKBOX  | WS_CHILD | WS_VISIBLE ,rc.right-rc.left-340 ,rc.bottom-rc.top-55,230,25,parent,(HMENU)ID_CHECK1,hInst,0);

	if(hCheck == NULL)
	{
		MessageBox(NULL, "Create CHECKBOX Failed!", szAppName, MB_OK);
		return ;
	}
	SetDefaultFont(hCheck);
	
	return;
}// end create_btns 
void create_tab(HWND parent)
{
	RECT tr = {0};
	GetWindowRect(hwnd,&tr);
	tr.bottom-=60;

    hwndTab = CreateWindowEx(
               0,                      // extended style
               WC_TABCONTROL,          // tab control constant
               "",                     // text/caption
               WS_CHILD | WS_VISIBLE,  // is a child control, and visible
               0,                      // X position - device units from left
               0,                      // Y position - device units from top
               160,                    // Width - in device units
               tr.bottom-tr.top,                    // Height - in device units
               parent,            // parent window
               NULL,                   // no menu
               hInst,                 // instance
               NULL                    // no extra junk
           );

    if (hwndTab == NULL)
    {
        // tab creation failed -
        // are the correct #defines in your header?
        // have you included the common control library?
        MessageBox(NULL, "Tab creation failed", "Tab Example", MB_OK | MB_ICONERROR);
        return;
    }

    SetDefaultFont(hwndTab);
    // start adding items to our tab control
    TCITEM tie = {0};  // tab item structure
    CHAR pszTab1 [] = "用户消息";  // tab1's text
  

    // set up tab item structure for Tab1
    tie.mask = TCIF_TEXT;  // we're only displaying text in the tabs
    tie.pszText = pszTab1;  // the tab's text/caption

    // attempt to insert Tab1
    if (TabCtrl_InsertItem(hwndTab, 0, &tie) == -1)
    {
        // couldn't insert tab item
        DestroyWindow(hwndTab);
        MessageBox(NULL, "Couldn't add Tab1", "Tab Example", MB_OK | MB_ICONERROR);
        return;
    }
	
}
void StartCommonControls(DWORD flags)
{
	//load the common controls dll, specifying the type of control(s) required 
	INITCOMMONCONTROLSEX iccx;
	iccx.dwSize=sizeof(INITCOMMONCONTROLSEX);
	iccx.dwICC=flags;
	InitCommonControlsEx(&iccx);
}

BOOL AdjustListView(HWND hList, LV_ITEM *lv, int iItems)
{
   int i = iItems;
   int iRet;
   ListView_DeleteAllItems(hList);

   lv->mask = LVIF_TEXT;
   lv->iSubItem = 0;
   lv->pszText = LPSTR_TEXTCALLBACK;

   while(i > 0)
   {
      iRet = ListView_InsertItem(hList, lv);
      i--;
   }
   return TRUE;
}

void AddText(HWND hwnd, char *szTextIn, COLORREF crNewColor)
{
	char *Text = (char *)malloc(lstrlen(szTextIn) + 5);
	CHARFORMAT cf;
	int iTotalTextLength = GetWindowTextLength(hwnd);
	int iStartPos = iTotalTextLength;
	int iEndPos;

	strcpy(Text, szTextIn);
	strcat(Text, "\r\n");

	SendMessage(hwnd, EM_SETSEL, (WPARAM)(int)iTotalTextLength, (LPARAM)(int)iTotalTextLength);
	SendMessage(hwnd, EM_REPLACESEL, (WPARAM)(BOOL)FALSE, (LPARAM)(LPCSTR)Text);

	free(Text);

	cf.cbSize      = sizeof(CHARFORMAT);
	cf.dwMask      = CFM_COLOR | CFM_UNDERLINE | CFM_BOLD;
	cf.dwEffects   = (unsigned long)~(CFE_AUTOCOLOR | CFE_UNDERLINE | CFE_BOLD);
	cf.crTextColor = crNewColor;

	iEndPos = GetWindowTextLength(hwnd);
	iEndPos = 0;
	SendMessage(hwnd, EM_SETSEL, (WPARAM)(int)iStartPos, (LPARAM)(int)iEndPos);
	SendMessage(hwnd, EM_SETCHARFORMAT, (WPARAM)(UINT)SCF_SELECTION, (LPARAM)&cf);
	SendMessage(hwnd, EM_HIDESELECTION, (WPARAM)(BOOL)TRUE, (LPARAM)(BOOL)FALSE);

	SendMessage(hwnd, EM_LINESCROLL, (WPARAM)(int)0, (LPARAM)(int)1);

}

int create_textbox(HWND parent)
{
	RECT tr = {0};
	
	GetWindowRect(hwnd,&tr);
	tr.bottom-=60;
	/*
	hwndEdit = CreateWindowEx(0, "RICHEDIT", "RichTextBox",
         WS_CHILD | WS_CLIPCHILDREN | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         0, 0, 1, 1, parent, (HMENU)ID_RICH_EDIT, hInst, NULL);
	*/
	hwndEdit = CreateWindowEx(WS_EX_CLIENTEDGE, "RichEdit", NULL,
         WS_CHILD | WS_CLIPCHILDREN |WS_VISIBLE | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
         163, 1, tr.right-tr.left-163, tr.bottom-tr.top, parent, NULL, hInst, NULL);

	if( hwndEdit ==NULL)
	{
		MessageBox(NULL, "Create hwndEdit Failed!", szAppName, MB_OK);
		return -1;
	}
	//SendMessage(hwndEdit, EM_SETWORDBREAKPROCEX, (WPARAM)0, (LPARAM)NULL);

	cf.cbSize = sizeof (CHARFORMAT);  
	cf.dwMask = CFM_FACE | CFM_SIZE;
	cf.yHeight = 180;
	strcpy(cf.szFaceName, "MS Sans Serif");
	SendMessage(hwndEdit, EM_SETCHARFORMAT, (WPARAM)(UINT)0, (LPARAM)&cf);

	AddText(hwndEdit, "Hello world", RGB(0,0,0));
	
}// end create_textbox

int create_list(HWND parent)
{		
	RECT tr = {0}; // rect structure to hold tab size
        //TabCtrl_GetItemRect(hwndTab, 0, &tr);
	GetWindowRect(hwndTab,&tr);
	hList = CreateWindowEx(0, WC_LISTVIEW, "", WS_VISIBLE | WS_CHILD | WS_VSCROLL | LVS_REPORT |LVS_LIST,
         0, 18, tr.right-tr.left , tr.bottom - tr.top - 18, parent,(HMENU)IDC_LIST, hInst, NULL);
	//ListView_SetExtendedListViewStyle(hList, LVS_EX_FULLROWSELECT | LVS_EX_GRIDLINES);
	if(hList ==NULL) 
	{
		MessageBox(NULL, "Create hList Failed!", szAppName, MB_OK);
		return -1;
	}
	SendMessage(hList,LVM_SETEXTENDEDLISTVIEWSTYLE,
           0,LVS_EX_FULLROWSELECT); 

	ListView_SetView(hList, LV_VIEW_LIST);
	ShowScrollBar(hList,SB_VERT,TRUE);

	lvC.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT | LVCF_SUBITEM;
	lvC.fmt = LVCFMT_LEFT;

	index= 0;
	lvC.iSubItem = index;
	lvC.cx = 200;
	lvC.pszText = "news      ";
//	ListView_InsertColumn(hList,index,&lvC);
	myIndex++;
	strcpy(mterm[myIndex].name, "2010-02-12 ");
	strcpy(mterm[myIndex].content, "第二个listview 双击信息显示 ");
	myIndex++;
	AdjustListView(hList, &lv, myIndex);
}

int create_W(HINSTANCE h)
{
	WNDCLASS wndclass;

	wndclass.style = CS_HREDRAW | CS_VREDRAW;
	wndclass.lpfnWndProc = WndProc;
	wndclass.cbClsExtra = 0;
	wndclass.cbWndExtra = 0;
	wndclass.hInstance = h;
	wndclass.hIcon = LoadIcon(NULL, IDI_APPLICATION);
	wndclass.hCursor = LoadCursor(NULL, IDC_ARROW);
	//wndclass.hbrBackground = (HBRUSH) GetStockObject(WHITE_BRUSH);
	//wndclass.hbrBackground = (HBRUSH)(COLOR_BACKGROUND);
	wndclass.hbrBackground =  GetSysColorBrush(COLOR_BTNFACE);
	wndclass.lpszMenuName = NULL;

	wndclass.lpszClassName = szAppName;

	if (!RegisterClass(&wndclass)) {
    		MessageBox(NULL, TEXT("This program requires Windows NT!"), szAppName, MB_ICONERROR);
    		return 0;
	}
	
	hwnd = CreateWindow(szAppName,
		"金钥匙 - 消息提示板",
		WS_OVERLAPPEDWINDOW,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		NULL,
		NULL,
		h,
		NULL);

	ShowWindow(hwnd, 1);
	UpdateWindow(hwnd);
	return 1;
}
int init()
{
	char self[1024];
	char *pt;
	hwnd = NULL;
	hList = NULL;
	hwndEdit = NULL;
	index = 0;
	StartCommonControls(ICC_LISTVIEW_CLASSES);
	if_quit = 0;

	memset(current_dir,0,1024);
	memset(current_exe_name,0,256);
	memset(self,0,1024);
	GetModuleFileName(NULL,(LPSTR)&self,1024);
	pt =get_last_name(self);
	strcpy( current_exe_name,pt);
	pt = get_dir(self);
	strcpy( current_dir,pt);

	memset(Buf,0,MSGLEN);
	memset(diskid,0,13);
	memset(day_buf,0,11);

	myIndex = 0;
	unix_time1 = time(NULL);
	unix_time2 = time(NULL);

}

int deinit()
{
	// unhook or something
	do_unhook();
}

int check_same_pros()
{
	HANDLE hSnap;
	PROCESSENTRY32 proc32;
	char self_name[256];
	char *pt;
	char cmd_line[256];
	DWORD self_pid;
	int ret;
	ret = 0;
	self_pid = -1;
	hSnap = NULL;
	memset(cmd_line,0,256);
	GetModuleFileName(NULL,(LPSTR)&self_name,256);
	if((hSnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0)) == INVALID_HANDLE_VALUE)
	return -1;
	proc32.dwSize=sizeof(PROCESSENTRY32);
	
	self_pid = GetCurrentProcessId();
	pt = get_last_name(self_name);
	printf("self_pid=%d, self_name=%s\n",self_pid, pt);
	while((Process32Next(hSnap, &proc32)) == TRUE )
	{ 
		//printf("%s\n", proc32.szExeFile,self_name);
		if( self_pid != proc32.th32ProcessID && stricmp(proc32.szExeFile,pt) == 0)
		{
			printf("got the same exe %d\n", proc32.th32ProcessID);
			sprintf(cmd_line,"taskkill /pid %d", proc32.th32ProcessID);
			ret = system(cmd_line);
			
		}
	}

	CloseHandle(hSnap); 
	return ret;

}

int main( int argc,char**argv)
{
	MSG msg;
	HINSTANCE   hRichEdit; 
	LPARAM lParam = 0;
	init(); 

	printf("main\n");
	if( argc < 2) return 0;

	memcpy(diskid,argv[1], strlen(argv[1]));	
	
	get_now_time();
	get_now_day();
	ck();

	hRichEdit = LoadLibrary("RICHED32.DLL");
	hInst= GetModuleHandle (0);
	create_W(hInst);
	create_tab(hwnd);
	create_list(hwndTab);
	create_textbox(hwnd);
	create_btns(hwnd);

	check_config(); // everyday every time startup to check if it is the up to date
	check_loop();

	EnumWindows(EnumFunc,(LPARAM)0);

	while (GetMessage(&msg, NULL, 0, 0)) 
	{
		unix_time2 = time(NULL);
		if( unix_time2 - unix_time1 > 10)
		{
			unix_time1 = time(NULL);
			check_loop();
		}
	
		TranslateMessage(&msg);
    		DispatchMessage(&msg);
		
	}

	FreeLibrary(hRichEdit);
	return msg.wParam;
}

LRESULT CALLBACK WndProc(HWND hwnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	HDC hdc;
	PAINTSTRUCT ps;
	RECT rect;
	int w,h;
	
	switch (message) 
	{	
		/*
		case WM_PAINT:
		{
			hdc = BeginPaint(hwnd, &ps);
			GetClientRect(hwnd, &rect);
			DrawText(hdc, TEXT("Hello world!"), -1, &rect, DT_SINGLELINE | DT_CENTER | DT_VCENTER);
			EndPaint(hwnd, &ps);
			return 0;
		}break;
		*/
		case WM_COMMAND:
		{
			switch(LOWORD(wParam))
			{
				case ID_CHECK1:
				{
					int state = SendMessage(hCheck,BM_GETCHECK,0,0);
					if(state == BST_CHECKED) //yes it is checked
					{
						write_config();
					}
					else
					{	
						del_config();						
					}
				}break;
				case ID_BUTTON1:
				{
					//hide the window
					ShowWindow(hwnd,SW_HIDE);
					UpdateWindow(hwnd);
					//PostQuitMessage(0);	
				}
			}
		}break;
		case WM_CLOSE:
		{
			if_quit = 1;
			
			DestroyWindow(hwnd);
		}break;
		case WM_DESTROY:
		{
			deinit();
			PostQuitMessage(0);
		}
		break;
		case WM_SIZE:
		{
			RECT rc={0};
			GetWindowRect(hwnd,&rc);
			w = LOWORD(lParam); h = HIWORD(lParam); h-=30;
			SetWindowPos(hwndTab, HWND_TOP, 0,0,160,h, SWP_NOMOVE);
			SetWindowPos(hwndEdit, HWND_TOP,0,0,w - 163,h, SWP_NOMOVE); 
			SetWindowPos(button, HWND_TOP , rc.right-rc.left-70, rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
			SetWindowPos(hCheck, HWND_TOP,  rc.right-rc.left-340,rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
		}break;
		case WM_NOTIFY :
			switch(((LPNMHDR)lParam)->code)
      			{
      				case NM_DBLCLK :
			        	hdr = (NMHDR FAR*)lParam;
         				if(hdr->hwndFrom == hList)
         				{
            					index = ListView_GetNextItem(hList,-1,LVNI_SELECTED);
            					if(index != -1)
            					{
               						//MessageBox(hwnd, "index", "Doubleclicked on this item", MB_OK);
							AddText(hwndEdit, mterm[ index ].content , RGB(0,0,0));
            					}
         				}
         			break;
      				case LVN_GETDISPINFO :
         				lvd = (LV_DISPINFO FAR*)lParam;
         				if((((LPNMHDR)lParam)->hwndFrom == hList))
         				{
            					switch(lvd->item.iSubItem)
            					{
            						case 0:
               							lvd->item.pszText =  mterm[lvd->item.iItem].name;
               						break;
            						               						
            					}            			
         				}
				break;	
      			}
		break;
		default:break;
	}

	return DefWindowProc(hwnd, message, wParam, lParam);

}

LRESULT CALLBACK EnumFunc(HWND hWnd,LPARAM lParam)
{
	static int count = 0;
	char pszFileName [100];
	GetWindowText(hwnd,pszFileName,100);
	if(strstr(pszFileName,"Goldrockfx"))
	{
		hwnd = hWnd;
	}
	return TRUE;
}
