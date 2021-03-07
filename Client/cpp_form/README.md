# C++ Windows Form

##### Creating startup C++ windows form workaround
* Create a new CLR Empty Project
* Open Project Properties and set the following values
  * Linker -> Systems -> SubSystems = **Windows (/SUBSYSTEM:WINDOWS)**
  * Linker -> Advanced -> Entry Point = **main** (entry point function name)
* Add -> Visual C++ -> Windows Form
* Ignore the error page shown
* Copy the following code to MyForm.cpp (or any form name specified in the add windows form step)
    ```C++
    using namespace System;
    using namespace System::Windows::Forms;

    [STAThreadAttribute]
    int main(array <String^> ^ args) {
    	Application::EnableVisualStyles();
    	Application::SetCompatibleTextRenderingDefault(false);
    	CppWindowsForm::MyForm form;
    	Application::Run(%form);
    	return 0;
    }
    ```
* Fix the namespace error
* Close all files and close solution


##### Using windows form toolbox
* Open solution
* Double click on MyForm.h,the toolbox could be used properly now