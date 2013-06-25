<?php


    function do_upload()
    {
        $field = 'input_name'; // input file "name" buraya geliyor.

        if( empty($_FILES[$field]['name']))
        {
            // sess_set_flash('upload_error_'.$field, 'Lütfen bilgisayarınızdan bir resim seçin !');

            redirect('');
        }

        //---------- Check Mime Type -----------//

        $mime_type  = $_FILES[$field]['type'];
        $jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');

        if ( ! in_array($mime_type, $jpeg_mimes, true))
        {
            // sess_set_flash('upload_error_'.$field, 'Bu format desteklenmiyor ! Yalnızca .jpeg yada .jpg uzantılı dosyaları yükleyiniz.');

            redirect('');
        }

        //---------- Check File Size -----------//

        // extract file extension
        $ext = substr($_FILES[$field]['name'],(strripos($_FILES[$field]['name'], '.') + 1));

        if($_FILES[$field]['size'] / 1024 > 1024)  // file size check
        {
            // sess_set_flash('upload_error_'.$field, '1 mb dan büyük dosyaları yükleyemiyoruz, lütfen resminizin boyutunu düşürmeyi deneyin.');

            redirect('');
        }

        //---------- Create Unique filename -----------//

        $ext       = strtolower($ext);
        $filename  = uniqid(time());

        //---------- Check Extension -----------//

        $allowed_extensions = array(
        'jpeg' => 'Jpeg',
        'jpg'  => 'Jpeg',
        );

        if( ! isset($allowed_extensions[$ext]))
        {
            // sess_set_flash('upload_error_'.$field, 'Bu format desteklenmiyor ! Yalnızca .jpeg yada .jpg uzantılı dosyaları yükleyiniz.');

            redirect('');
        }

        if(is_uploaded_file($_FILES[$field]['tmp_name']))
        {
            $filepath = "/dosyanin/yuklenecegi/path/filename.ext"; // MODULES .'images'. DS .'temp'. DS .$filename. '.'.$ext;

            /*
             * Move the file to the final destination
             * To deal with different server configurations
             * we'll attempt to use copy() first.  If that fails
             * we'll use move_uploaded_file().  One of the two should
             * reliably work in most environments
             */
            if ( ! @copy($_FILES[$field]['tmp_name'], $filepath))
            {
                if ( ! @move_uploaded_file($_FILES[$field]['tmp_name'], $filepath))
                {
                     exit('Geçici dosya sunucuya yazılamadı ! Lütfen bizimle irtibata geçin. ( Temp File Write Error ) ');
                }
            }

            //--------------------- BAŞARILI

            // Dosya yüklendi !! .

            redirect('');
        }
        else
        {
            exit('Bir hata oluştu ve resminizi yükleyemedik !');
        }
    }
