/*
gcc gk4.c -o gk4 -mwindows -Wl,-subsystem,console -lcomctl32 
gcc gk4.c -o gk4 -mwindows -lcomctl32

gk4.c 
the main gui part with comctl
CoInitializeEx 

*/
#define _WIN32_IE 0x0400
#define _WIN32_WINNT  0x500


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
#define ID_FILE_NEW 8334


#define __strtok_r strtok_r
#define __rawmemchr strchr
#define MSGLEN 8192*40
#define WAIT_TIME 1000*60*30 
#define MSGMAX 20
#define MAGICNUMBER 9999
#define WAITTIME 20*60  // 20 mins
#define SELF_EXE "gk4.exe"

//curl -s -d "action=vipnews&DiskId=001916005802&D_msgtime=2011-03-26 12:33:13" -k https://127.0.0.1/whsoft/WHSoft/DLL/SoftFind.asp
//char self[1024];

char * asp_path = "http://218.240.38.44/DLL/SoftFind.php";
//char * asp_path = "http://127.0.0.1/whsoft/WHSoft/DLL/SoftFind.php";
char Buf[MSGLEN];
char time_buf[21];
char day_buf[11];
// 001 916 005 802
char diskid[256];
int unix_time1;
int unix_time2;

LV_ITEM lvitem;

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
LRESULT CALLBACK goldkey_popup_proc(HWND , UINT , WPARAM , LPARAM );

void SetText(HWND , char *, COLORREF );
int deinit();
int init();
DWORD WINAPI MyThreadLoop( LPVOID lpParam );
DWORD WINAPI GUIThreadLoop( LPVOID lpParam );
static char szAppName[] = "GoldKey_Window";

HINSTANCE hInst;
HWND hwnd; // the core window
HWND mt4_hwnd;
HWND popup_hwnd[MSGMAX]; // the pop up window 
HWND popup_edit[MSGMAX]; // the edit control of pop up window
HWND hList; // the left list
HWND hwndEdit;
HWND hwndTab;
HWND button;
HWND hCheck;
static int index; // for the List using
Item ListItem[1];
myIterm mterm[100]; // only list 100 items 
int myIndex; /// for myIterm count
int myIndex2; // for parseMsg counting
int all_count; // count for all existed hcwt files
LV_ITEM lv;
LV_COLUMN lvC;
LV_DISPINFO *lvd;
NMHDR *hdr;

CHARFORMAT cf;
CREATESTRUCT  *cs;

int if_quit;
//int wait_time = 1000*60*30;// half an hour
char current_dir[MAX_PATH];
char current_exe_name[256];

typedef void (*pfunc)();
typedef void (*pfunc2)();
pfunc2  hkprcSysMsg; 
pfunc mt4_func;
static   HINSTANCE   hinstDLL;  
static   HHOOK   hhookSysMsg;   
HANDLE hThread[2];
int unread[MSGMAX];
int unread_index;


int delete_all_file(char*fname)
{
        char fpath[MAX_PATH];
        HANDLE hFind;
        WIN32_FIND_DATA FindFileData;
        memset(fpath,0,MAX_PATH);
        sprintf(fpath,"*.%s",fname);
        if((hFind = FindFirstFile(fpath, &FindFileData)) != INVALID_HANDLE_VALUE)
        {
                do{
                        printf("%s\n", FindFileData.cFileName);
                        DeleteFile(FindFileData.cFileName);

                }while(FindNextFile(hFind, &FindFileData));
                FindClose(hFind);
		return 1;
        }else
        {
                FindClose(hFind);
                printf("no found\n");
        }
        return 0;
}


LRESULT CALLBACK EnumFunc(HWND hWnd,LPARAM lParam)
{
	static int count = 0;
	char pszFileName [100];
	GetWindowText(hWnd,pszFileName,100);
	if(strstr(pszFileName,"Goldrockfx"))
	{
		mt4_hwnd = hWnd;
	}
	return TRUE;
}

void remove_menu()
{
	HMENU hmenu;
	EnumWindows((WNDENUMPROC)EnumFunc,(LPARAM)0);
	if( mt4_hwnd != NULL)
	{
		hmenu = GetMenu(mt4_hwnd);
		RemoveMenu(hmenu, ID_FILE_NEW, MF_BYCOMMAND);
		UpdateWindow(mt4_hwnd);
	}
}

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
//	FreeLibrary(hinstDLL);
	return 0;
}

int do_unhook()
{
//	hinstDLL   =   LoadLibrary((LPCTSTR)"test3.dll");
	hkprcSysMsg = (pfunc2)GetProcAddress(hinstDLL, "unSetHooked");
	if( hkprcSysMsg ==NULL) 
	{
//	    	MessageBox(NULL,"GetProcAddress3 error ","Info",IDOK);
    		printf("%d\n,",GetLastError());
	    	return -1;		
	} 
	mt4_func = (pfunc)GetProcAddress(hinstDLL, "GetUnHooked");
	if( mt4_func ==NULL) 
	{
  //  		MessageBox(NULL,"GetProcAddress4 error ","Info",IDOK);
	    	printf("%d\n,",GetLastError());
    		return -1;		
	}

	hkprcSysMsg();
	mt4_func();
	FreeLibrary(hinstDLL);

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

        static char buf[MAX_PATH];
        char*pch;
        static char*pch2;
        char*sv;
        memset(buf,0,MAX_PATH);
        strcpy(buf,f);

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

/*
char *get_last_name(char*f)
{

	char buf[MAX_PATH];
	
	char*pch;
	char*pch2;
	strcpy(buf,f);
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
*/

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
	
	static LV_ITEM lvt;
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
	

	if( myIndex < 20)
	{
		printf("myIndex=%d\n",myIndex);
		memset( mterm[myIndex].name, 0, 25);
		memset( mterm[myIndex].content, 0 , 4096);

//		memset(&lvt,0,sizeof(lvt)); 
		strcpy(mterm[myIndex].name, pt);
		strcpy(mterm[myIndex].content, str_buf);
		myIndex++;

		AdjustListView(hList, &lv, myIndex);

		/*
		lvt.mask=LVIF_TEXT;   // Text Style
		lvt.cchTextMax = 200; // Max size of test
		lvt.iItem=myIndex;          // choose item  
		lvt.iSubItem=0;       // Put in first coluom
		lvt.pszText= mterm[myIndex].name; // Text to display (can be from a char variable) (Items)
		*/
	//	SendMessage(hList,LVM_SETITEM,0,(LPARAM)&lvt); 
//		SendMessage(hList,LVM_INSERTITEM,0,(LPARAM)&lvt);
//		ListView_InsertItem(hList,&lvt);
	
		UpdateWindow(hwnd);
		UpdateWindow(hList);
		
	}else myIndex =0;
	
	return 0;
}
int check_hcwt(char*dir)
{
	int i;
	char fpath[1024];
	char cmd[2048];
	HANDLE hFind;
	WIN32_FIND_DATA FindFileData;
	memset(fpath,0,1024);
	memset(cmd,0,2048);
	sprintf(fpath,"%s\\*.hcwt",dir);
	sprintf(cmd,"del %s",fpath);
//	printf("dir=%s\n",dir);
	i = 0;
	if((hFind = FindFirstFile(fpath, &FindFileData)) != INVALID_HANDLE_VALUE)
	{
		do{
			i++;
			all_count ++;
		}while(FindNextFile(hFind, &FindFileData));
		FindClose(hFind);
	}else
	{
		printf("check_hcwt no hcwt found\n");
	}
	if( i >= 20) 
	{
		delete_all_file("hcwt");
//		system("del *.hcwt");
		all_count = 0;
	}	
}
int scan_hcwt(char*dir)
{
	char fpath[MAX_PATH];
	HANDLE hFind;
	WIN32_FIND_DATA FindFileData;
	memset(fpath,0,MAX_PATH);
	sprintf(fpath,"%s\\*.hcwt",dir);
//	printf("dir=%s\n",dir);
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
	char * saveptr1;
	fname =NULL; content = NULL;
	fp = NULL;
	memset(msg2,0,MSGLEN);
	memcpy(msg2,msg,strlen(msg));
	if(strchr(msg2,'|'))
	{
	
		pch = strtok_r(msg2,"|",&saveptr1);
		pch = strtok_r(NULL,"|",&saveptr1);
		if( pch !=NULL)
			fname = pch;
		pch = strtok_r(NULL,"|",&saveptr1);
		if(pch != NULL)
		pch = strtok_r(NULL,"|",&saveptr1);
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
//			printf("fname_buf = %s\n", replace(replace(fname_buf," ","_"),":","#"));
			if( all_count + 1 >= 20)
			{
				delete_all_file("hcwt");
//				system("del *.hcwt");
				all_count = 0;
			}
			fp = fopen(pt, "w" );
			if(fp!=NULL)
			{
				fwrite(content,1, strlen(content),fp);
				fclose(fp);
				
			//	scan_hcwt(current_dir);
				SetText(popup_edit[unread_index], content , RGB(0,0,0));
				ShowWindow(popup_hwnd[unread_index],1);
				unread_index++;
				all_count ++;

			}else {printf("fopen orror\n"); return -1;}

			
		}else
		{
	//		scan_hcwt(current_dir);
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

	printf("check_loop\n");
	memset(cmd_line,0,1024);
	
	a  = 0;
//	sprintf(cmd_line, " -s -d \"action=vipnews&DiskId=%s&D_msgtime=2011-03-26 12:33:13\"  %s",diskid,asp_path);
	sprintf(cmd_line, " -s -d \"action=vipnews&DiskId=%s&D_msgtime=%s\"  %s",diskid,time_buf,asp_path);

	sep[0] = (char)28;
	sep[1] = '\0';



	if( file_exists("curl.exe") == 1 /*&& file_exists("config") == 0 */)
	{
		if( if_quit == 0)
		{
			// code here
			memset(Buf,0,MSGLEN);
			a = run_cmd(cmd_line,"");
		
			if(a)
			{
				unread_index = 0;
				if(strstr(Buf, sep))
				{
					printf("Buf %s\n",Buf);
					pch = strtok_r(Buf,sep,&saveptr1);
					i = 0;
					while(pch!=NULL)
					{
						
						parse_vipmsg( pch);
						pch = strtok_r(NULL,sep,&saveptr1);
						if(pch == NULL) break;
					}
				}else
				{
					if( strchr(Buf,'|'))
					{
						parse_vipmsg(Buf);
					}
					
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
	unread_index = 0;
	scan_hcwt(current_dir);
	return ;
}

int ck()
{

	char self[256];
//	sprintf(self,"%s", argv[0]);
	char *pt;
	memset(self,0,256);

	GetModuleFileName(NULL,(LPSTR)&self,256);
//	printf("self = %s\n", self);
//	pt = get_last_name(self);
//	printf("-- %s\n",pt);

	pt = get_dir(self);
	printf("-- %s\n", pt);
	chdir(pt);
	//SetCurrentDirectory(pt); <-- this is dir
	check_hcwt(pt); // first we check if there are more than 20 hcwt files,delete them all 

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

void SetText(HWND hwnd, char *szTextIn, COLORREF crNewColor)
{
	char *Text = (char *)malloc(lstrlen(szTextIn) + 5);
	CHARFORMAT cf;
	int iTotalTextLength = GetWindowTextLength(hwnd);
//	int iStartPos = iTotalTextLength;
	int iEndPos;
	int iStartPos = 0;

	strcpy(Text, szTextIn);
//	strcat(Text, "\r\n");
	SetWindowText(hwnd,"");
//	SendMessage(hwnd, EM_SETSEL, (WPARAM)(int)iTotalTextLength, (LPARAM)(int)iTotalTextLength);
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

void AddText(HWND hwnd, char *szTextIn, COLORREF crNewColor)
{
	char *Text = (char *)malloc(lstrlen(szTextIn) + 5);
	CHARFORMAT cf;
	int iTotalTextLength = GetWindowTextLength(hwnd);
	int iStartPos = iTotalTextLength;
	int iEndPos;


	strcpy(Text, szTextIn);
	strcat(Text, "\r\n");
	SetWindowText(hwnd,"");
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
	SendMessage(hwndEdit, EM_SETREADONLY, (WPARAM)TRUE, (LPARAM)0);

	AddText(hwndEdit, "双击左边条目显示消息内容", RGB(0,0,0));
	
}// end create_textbox

int create_list(HWND parent)
{		
	RECT tr = {0}; 
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

	lvC.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT ;
	lvC.fmt = LVCFMT_LEFT;

	index= 0;
	myIndex = 0;
	lvC.iSubItem = index;
	lvC.cx = 200;
	lvC.pszText = "news      ";
	ListView_InsertColumn(hList,index,&lvC);
	
//	strcpy(mterm[myIndex].name, "2010-02-12");
//	strcpy(mterm[myIndex].content, "第二个listview 双击信息显示 ");
//	myIndex++;

	//lv.pszText= mterm[myIndex].name; // Text to display (can be from a char variable) (Items)
	
//	SendMessage(hList,LVM_SETITEM,0,(LPARAM)&lv); 
//	SendMessage(hList,LVM_INSERTITEM,0,(LPARAM)&lv);
//	myIndex ++;
///	AdjustListView(hList, &lv, myIndex);
//	scan_hcwt(current_dir);
}

int create_popup_window(HWND parent)
{
	RECT tr = {0};
	int sW;
	int sH;
	int i;
	sW = GetSystemMetrics(SM_CXSCREEN);
	sH = GetSystemMetrics(SM_CYSCREEN);

	for(i = 0; i < MSGMAX; i ++)
	{
		popup_hwnd[i] = CreateWindowEx(WS_EX_TOOLWINDOW,"Goldkey_popup",
		"金钥匙 - 消息提示板",
		WS_POPUPWINDOW|WS_CAPTION|WS_CHILD,
		sW- 350,
		sH- 280,
		350,
		250,
		NULL,
		NULL,
		hInst,
		NULL);
	

		if( popup_hwnd[i] == NULL)
		{
			MessageBox(NULL, "Create popup hwnd Failed!", szAppName, MB_OK);
			return -1;
		}

		popup_edit[i] = CreateWindowEx(WS_EX_CLIENTEDGE, "RichEdit", NULL,
        	 WS_CHILD | WS_CLIPCHILDREN |WS_VISIBLE | ES_WANTRETURN | ES_MULTILINE | WS_VISIBLE | ES_LEFT,
	         1, 1, 350, 250, popup_hwnd[i], NULL, hInst, NULL);

		if(popup_edit[i] == NULL)
		{
			MessageBox(NULL, "Create popup edit  Failed!", szAppName, MB_OK);
			return -1;
		}
		//SendMessage(hwndEdit, EM_SETWORDBREAKPROCEX, (WPARAM)0, (LPARAM)NULL);
		cf.cbSize = sizeof (CHARFORMAT);  
		cf.dwMask = CFM_FACE | CFM_SIZE;
		cf.yHeight = 180;
		strcpy(cf.szFaceName, "MS Sans Serif");
		SendMessage(popup_edit[i] , EM_SETCHARFORMAT, (WPARAM)(UINT)0, (LPARAM)&cf);
		AddText( popup_edit[i], "No Messages", RGB(0,0,0));	
		ShowWindow(popup_hwnd[i],SW_HIDE);
	}
	return 0;
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

        wndclass.lpszClassName = "Goldkey_popup";
        wndclass.lpfnWndProc = (WNDPROC)goldkey_popup_proc;

        if (!RegisterClass(&wndclass)) {
                MessageBox(NULL, TEXT("This program requires Windows and RegisterClass()!"), szAppName, MB_ICONERROR);
                return 0;
        }

	hwnd = CreateWindowEx(WS_EX_TOOLWINDOW,szAppName,
		"金钥匙 - 消息历史",
		WS_OVERLAPPEDWINDOW,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		CW_USEDEFAULT,
		NULL,
		NULL,
		h,
		NULL);

	create_popup_window(hwnd);

	ShowWindow(hwnd, SW_HIDE); // 1 for release
	UpdateWindow(hwnd);
	
	return 1;
}
int init()
{
	char self[MAX_PATH];
	char *pt;
	hwnd = NULL;
	hList = NULL;
	hwndEdit = NULL;
	index = 0;
	StartCommonControls(ICC_LISTVIEW_CLASSES);
	if_quit = 0;

	memset(current_dir,0,MAX_PATH);
	memset(current_exe_name,0,256);
	memset(self,0,MAX_PATH);
	GetModuleFileName(NULL,(LPSTR)&self,MAX_PATH);
	pt =get_last_name(self);
	strcpy( current_exe_name,pt);
	pt = get_dir(self);
	strcpy( current_dir,pt);

	memset(Buf,0,MSGLEN);
	memset(diskid,0,256);
	memset(day_buf,0,11);

	myIndex = 0;
	myIndex2 = 0;
	unix_time1 = time(NULL);
	unix_time2 = time(NULL);

	lv.cchTextMax = 200;
	hThread[0] = NULL;
	hThread[1] = NULL;
	
	memset(unread,MAGICNUMBER, MSGMAX);
	unread_index = 0;
	
	all_count = 0;	

}

int deinit()
{
	// unhook or something
	remove_menu();
	do_unhook();
//	TerminateThread(hThread[0],NULL);
	TerminateThread(hThread[1],0);
	if_quit = 1;
}

int check_process_on(DWORD self_pid,char*self_name)
{

	HANDLE hSnap;
	PROCESSENTRY32 proc32;
	char *pt;
	int ret;
	ret = 0;
	hSnap = NULL;

	if((hSnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0)) == INVALID_HANDLE_VALUE)
	return -1;
	proc32.dwSize=sizeof(PROCESSENTRY32);
	
	pt = self_name;
	printf("self_pid=%d, self_name=%s\n",self_pid, pt);

	if((hSnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0)) == INVALID_HANDLE_VALUE)
	return -1;
	proc32.dwSize=sizeof(PROCESSENTRY32);
	while((Process32Next(hSnap, &proc32)) == TRUE )
	{ 
		//printf("%s\n", proc32.szExeFile,self_name);
		if( self_pid != proc32.th32ProcessID && stricmp(proc32.szExeFile,pt) == 0)
		{
			CloseHandle(hSnap);	
			printf("got the same exe %d\n", proc32.th32ProcessID);
			ret = proc32.th32ProcessID;
			return ret;			
		}
	}

	CloseHandle(hSnap); 
	return ret;
}

int check_same_pros()
{

	HANDLE hSnap;
	PROCESSENTRY32 proc32;;
	
	char self_name[256];
	char *pt;
	char cmd_line[256];
	DWORD self_pid;
	int i;
	int ret;
	ret = 0;
	i = 0;
	self_pid = -1;

	memset(cmd_line,0,256);
//	GetModuleFileName(NULL,(LPSTR)&self_name,256);
	self_pid = GetCurrentProcessId();
//	pt = get_last_name(self_name);
	printf("self_pid=%d, self_name=%s\n",self_pid, current_exe_name);

	for(i = 0; i < 5; i++)
	{
		if((hSnap = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, 0)) == INVALID_HANDLE_VALUE)
			return -1;
		proc32.dwSize=sizeof(PROCESSENTRY32);
		while((Process32Next(hSnap, &proc32)) == TRUE )
		{
			if( self_pid != proc32.th32ProcessID && stricmp(proc32.szExeFile,SELF_EXE) == 0)
			{
				
				printf("got the same exe %d\n", proc32.th32ProcessID);
				ret = proc32.th32ProcessID;
				break;
			}
		}
		CloseHandle(hSnap); 
		if( ret != 0 && ret != -1)
		{
			sprintf(cmd_line,"/F /pid %d", ret);
//			MessageBox(NULL,cmd_line,"info",MB_OK);
			ShellExecute(NULL,"open","taskkill",cmd_line,NULL,SW_HIDE);
			memset(cmd_line,0,256);	
		}else if( ret == 0 || ret == -1) break;		
		
		ret = 0;
		Sleep(500);
	}

	return ret;

}

DWORD WINAPI MyThreadLoop( LPVOID lpParam )
{
	
	while(if_quit == 0)
	{
		
		check_loop();
		Sleep( WAITTIME*1000);
		if( if_quit == 1) break;
	}
	
	return 0;
}

DWORD WINAPI GUIThreadLoop( LPVOID lpParam )
{
	MSG msg;
	create_W(hInst);
	create_tab(hwnd);
	create_list(hwndTab);
	create_textbox(hwnd);

	while (GetMessage(&msg, NULL, 0, 0)) 
	{
	
		TranslateMessage(&msg);
    		DispatchMessage(&msg);
		
	}
	return msg.wParam;
}

int main( int argc,char**argv)
{
	
	HINSTANCE   hRichEdit; 
	DWORD dwGenericThread,dwGThread2;;
//	SECURITY_ATTRIBUTES lsa; 
	LPARAM lParam = 0;

//	ShowWindow(  GetConsoleWindow(), SW_HIDE );
 
	if( argc < 2) return 0;

	if( strstr(argv[1],"-q"))
	{
		// call to kill others and self
		init();
		check_same_pros();
//		Sleep(500);
		exit(0);
		
	}
	memcpy(diskid,argv[1], strlen(argv[1]));
	if( strlen(diskid ) < 8 ) 
	{
		MessageBox(NULL, "安装不正确", "info", MB_OK);
		return -1;
	}
	init();
	check_same_pros();
		
	get_now_time();
	get_now_day();
	remove_menu();// try to remove the menu item,if there is a menu iterm on mt4 main window

	hRichEdit = LoadLibrary("RICHED32.DLL");
	hInst= GetModuleHandle (0);

//	create_btns(hwnd);
//	check_config(); // everyday every time startup to check if it is the up to date
//	check_loop();

	hThread[0] = CreateThread(NULL,0,GUIThreadLoop,NULL,0,&dwGThread2);
	if( hThread[0] == NULL)
	{
		printf("hThread 0 Create failed\n");
		exit(-1);
	}

	hThread[1] = CreateThread(NULL,0,MyThreadLoop,NULL,0,&dwGenericThread);
	if( hThread[1] == NULL)
	{
		exit(-1);
	}
	ck();
	do_hook();
	WaitForMultipleObjects(2, hThread, TRUE, INFINITE);

	FreeLibrary(hRichEdit);
	return 0;

}

LRESULT CALLBACK WndProc(HWND hwnd, UINT message, WPARAM wParam, LPARAM lParam)
{
	HDC hdc;
	PAINTSTRUCT ps;
	RECT rect;
	int w,h;
	NMLVDISPINFO* plvdi;
	switch (message) 
	{	
		case WM_COMMAND:
		{
			switch(LOWORD(wParam))
			{
				/*
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
				*/
				case ID_BUTTON1:
				{
					//hide the window
					ShowWindow(hwnd,SW_HIDE);
					UpdateWindow(hwnd);
					//PostQuitMessage(0);	
				}
			}
		}break;
		case WM_SYSCOMMAND:
		{
			switch( wParam)
			{
				case SC_CLOSE:
				{
					ShowWindow(hwnd,SW_HIDE);
					return 0;	
				}break;
				default:
				return DefWindowProc(hwnd, message, wParam, lParam);
			}
		}
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
			/*
			SetWindowPos(button, HWND_TOP , rc.right-rc.left-70, rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
			SetWindowPos(hCheck, HWND_TOP,  rc.right-rc.left-340,rc.bottom-rc.top-53,0,0, SWP_NOSIZE);
			*/
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
							SetText(hwndEdit, mterm[ index ].content , RGB(0,0,0));
            					}
         				}
         			break;
      				case LVN_GETDISPINFO:
				{
         			//	m = (LV_DISPINFO FAR*)lParam;
					plvdi = (NMLVDISPINFO*) lParam; 
         				if((((LPNMHDR)lParam)->hwndFrom == hList))
         				{
            					switch(plvdi->item.iSubItem)
            					{
            						case 0:
               							plvdi->item.pszText =  mterm[plvdi->item.iItem].name;
               						break;
            						               						
            					}            			
         				}
				}break;	
      			}
		break;
		default:break;
	}

	return DefWindowProc(hwnd, message, wParam, lParam);

}

LRESULT CALLBACK goldkey_popup_proc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
        switch(message)
        {
		case WM_DESTROY:
                case WM_CLOSE:
                {
			ShowWindow(hWnd,SW_HIDE);
                }break;

                default: return DefWindowProc(hWnd,message,wParam,lParam);
        }

        return 0;
}
