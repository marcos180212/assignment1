    const dateReg = new RegExp(/([12]\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])/);

   function validate() {
        const alertdoc = document.getElementById('warning');
      if( document.forms['myForm'].name.value == "" ) {
         alertdoc.innerText = 'Please input a first name, last name or full name.';
          document.forms['myForm'].name.focus();
         return false;
      } else {
          return( true );
      }
   }



    function addvalidate() {

        const alertdoc = document.getElementById('warning');
        const datereg = new RegExp(/([12]\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])/);
        const isfirstletteruppercase = new RegExp(/^[A-Z]/);

        if( !document.forms['addnewemp'].bdate.value && !datereg.test(document.forms['addnewemp'].bdate.value) ) {
           alertdoc.innerText = 'Please input a valid birth date';
           document.forms['addnewemp'].bdate.focus();
           return false;

        } else if( document.forms['addnewemp'].firstName.value === "" && isfirstletteruppercase.test(document.forms['addnewemp'].firstName.value)) {
           alertdoc.innerText = 'Please input the first name - Upper case first letter.';
           document.forms['addnewemp'].firstName.focus();
           return false;

        } else if( document.forms['addnewemp'].lastName.value === "" && isfirstletteruppercase.test(document.forms['addnewemp'].firstName.value)) {
           alertdoc.innerText = 'Please input the last name - Upper case first letter.';
           document.forms['addnewemp'].lastName.focus();
           return false;
        } else if( !document.forms['addnewemp'].hdate.value && !datereg.test(document.forms['addnewemp'].bdate.value) ) {
           alertdoc.innerText = 'Please input a valid hire date!';
           document.forms['addnewemp'].hdate.focus();
           return false;

        } else if( !document.forms['addnewemp'].gender[0].checked && !document.forms['addnewemp'].gender[1].checked ) {

           alertdoc.innerText = 'Please choose between the gender options.';
            return false;
        } else {

        return( true );
        }
   }


    function updvalidate() {

        const alertdoc = document.getElementById('warning');
        const datereg = new RegExp(/([12]\d{3})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])/);
        const isfirstletteruppercase = new RegExp(/^[A-Z]/);

        if( !document.forms['updemp'].bdate.value && !datereg.test(document.forms['addnewemp'].bdate.value) ) {
            alertdoc.innerText = 'Please input a valid birth date';
            document.forms['updemp'].bdate.focus();
            return false;
        } else if( document.forms['updemp'].firstName.value == "" && isfirstletteruppercase.test(document.forms['addnewemp'].firstName.value)) {
            alertdoc.innerText = 'Please input the first name - Upper case first letter.';
            document.forms['updemp'].firstName.focus();
            return false;
        } else if( document.forms['updemp'].lastName.value == "" && isfirstletteruppercase.test(document.forms['addnewemp'].firstName.value)) {
            alertdoc.innerText = 'Please input the last name - Upper case first letter.';
            document.forms['updemp'].lastName.focus();
            return false;
        } else if( !document.forms['updemp'].hdate.value && !datereg.test(document.forms['addnewemp'].bdate.value) ) {
            alertdoc.innerText = 'Please input a valid hire date!';
            document.forms['updemp'].hdate.focus();
            return false;
        } else if( !document.forms['updemp'].gender[0].checked && !document.forms['updemp'].gender[1].checked ) {

            alertdoc.innerText = 'Please choose between the gender options.';
            return false;
        } else if( !document.forms['updemp'].usrFle.value) {

            alertdoc.innerText = 'Please input the employee picture!';

            return false;
        }else {
            return( true );
        }
    }