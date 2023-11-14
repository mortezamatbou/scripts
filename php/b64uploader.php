<?php

class b64uploader
{

    public $data = '';
    public $type = '';
    public $ext = '';
    public $size = 0;
    public $data_encoded = '';
    public $data_decoded = '';

    public $error = '';
    public $url_prefix = '';
    private $valid_extension = ['png', 'jpg', 'jpeg', 'gif'];

    // default maximum size for uploaded file
    private int $limit_size = 0;

    public function __construct(string $data = '')
    {
        $this->data = $data;
    }

    /**
     * @param $data string format: "data:image/{{this->valid_extension}};base64,{{file_encode_in_base64}}"
     * @return b64uploader
     */
    public function init(string $data = ''): b64uploader
    {
        if ($data) {
            $this->data = $data;
        }
        $this->export_base64_image();
        return $this;
    }

    /**
     * @return void if data format is not ok, set error message
     */
    private function export_base64_image(): void
    {
        $this->type = '';
        $this->data_decoded = '';
        $this->data_encoded = '';
        $this->error = '';

        $data_encoded = '';
        $data_decoded = '';
        $type = '';

        $data = $this->data;

        if (!$data) {
            return;
        }

        if (preg_match('/^data:image\/(\w+)-base64,/', $data, $type) || preg_match('/^data:application\/(\w+)-base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = isset($type[1]) ? strtolower($type[1]) : ''; // type to lowercase

            if (!in_array($type, $this->valid_extension)) {
                $this->error = 'فرمت فایل نامعتبر است';
                return;
            }

            $data_encoded = str_replace(' ', '+', $data);
            $data_decoded = base64_decode($data_encoded);

            if ($data_decoded == false) {
                $this->error = 'فرمت base64 اشتباه است';
                return;
            }
        } else {
            $this->error = 'فرمت فایل base64 نامعتبر است';
        }

        $this->data_decoded = $data_decoded;
        $this->data_encoded = $data_encoded;
        $this->type = $type;

    }

    /**
     * @param string $dir set destination for uploaded file /storage/funds/my_file_name.jpg
     * @param int $char_count length of uploaded file name
     * @return false|string
     */
    public function save(string $dir, int $char_count = 15, $check_unique_callback = NULL, $check_unique_params = [])
    {
        $this->error = '';
        if (!$dir || !$this->data_decoded || !$this->type) {
            $this->error = 'مقادیر مورد نیاز برای ایجاد فایل وجود ندارد';
            return false;
        }

        // Check uploaded file size
        if ($this->limit_size && $this->get_file_size('kilobyte') > $this->limit_size) {
            $this->error = "سایز فایل ارسالی نمیتواند از {$this->limit_size} کیلوبایت بیشتر باشد";
            return false;
        }

        $dir = substr($dir, strlen($dir) - 1) != '/' ? $dir . '/' : $dir;
        $file_name = $this->random_str($char_count) . '.' . $this->type;
        $put_content_dir = $dir;

        // check callback if isset
        if ($check_unique_callback) {
            $check_unique_params['column']['value'] = $put_content_dir . $file_name;
            $duplicated = $check_unique_callback($check_unique_params);
            if ($duplicated) {
                $this->save($dir, $char_count, $check_unique_callback, $check_unique_params);
            }
        }

        $put_content_dir = '';
        if (preg_match('/^[a-zA-Z1-9]/', $dir)) {
            $put_content_dir = './' . $dir;
        } else if (preg_match('/^\/[a-zA-Z1-9]/', $dir)) {
            $put_content_dir = '.' . $dir;
        }
        if (!file_put_contents($put_content_dir . $file_name, $this->data_decoded)) {
            $this->error = 'مشکلی در ایجاد فایل به وجود آمده است';
        }

        $header_ext = $this->check_file_ext_header($put_content_dir . $file_name);

        if (!$header_ext) {
            // remove uploaded file
            $this->error = 'فرمت فایل ارسالی نامعتبر است.';
            $uploaded_file = $put_content_dir . $file_name;
            if ($uploaded_file && file_exists($uploaded_file)) {
                unlink($uploaded_file);
            }
            return FALSE;
        }

        return ($this->url_prefix ?? '') . $dir . $file_name;
    }


    private function random_str($chars = 15)
    {
        $letters = 'abcefghijklmnopqrstuvwxyz1234567890';
        return substr(str_shuffle($letters), 0, $chars);
    }


    public function url_prefix($url_prefix = '')
    {

        if (!$url_prefix) {
            $this->url_prefix = '';
            return $this;
        }

        $pos = strlen($url_prefix) - 1;
        if (substr($url_prefix, $pos) == '/') {
            $url_prefix = substr_replace($url_prefix, "", $pos);
        }

        $this->url_prefix = $url_prefix;
        return $this;
    }

    /**
     * @param int $limit_size define limit size in Kilobyte
     * @return $this
     */
    public function set_limit(int $limit_size)
    {
        $this->limit_size = $limit_size;
        return $this;
    }

    public function get_file_size($conversion = 'byte'): float
    {
        $size = (strlen($this->data_encoded) * 3 / 4) - substr_count(substr($this->data_encoded, -2), '=');
        $result = $size;

        if ($size <= 0) {
            return $result;
        }

        switch (strtolower($conversion)) {
            case 'kilobyte':
                $result = $size / 1000;
                break;
            case 'megabyte':
                $result = $size / 1000000;
                break;
        }

        $this->size = $size / 1000;

        return $result;
    }

    public function set_valid_extensions(array $valid_extension)
    {
        $ext = [];
        foreach ($valid_extension as $row) {
            $ext[] = strtolower($row);
        }
        $this->valid_extension = $ext;
        return $this;
    }

    public function check_file_ext_header($file_dir)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_dir);
        finfo_close($finfo);

        if (!$mime) {
            return FALSE;
        }

        $mime_explode = explode('/', $mime);
        $ext = $mime_explode[1] ? strtolower($mime_explode[1]) : '';

        $this->ext = strtolower($ext);
        if (($ext == 'jpg' && in_array($this->type, ['jpg', 'jpeg'])) || ($ext == 'jpeg' && in_array($this->type, ['jpg', 'jpeg']))) {
            $this->type = $ext;
        }

        return in_array($ext, $this->valid_extension) && $this->type == $ext;
    }

}