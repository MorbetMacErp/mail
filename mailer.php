<?php
/**
 * At the moment 
 */
class Mailer
{
    /**
     * Email CHARSET
     * The character set of the message
     * 
     * @var string
     */
    public $CharSet = 'iso-8859-1';


    /**
     * The MIME content-type  
     * 
     * @var string
     */
    public $ContentType = 'text/html';


    /**
     * X-Mailer header value
     * Options: An empty string for default, whitespace for none, or a string to use.
     * 
     * @var string
     */
    public $XMailer = 'PHP/';
    
    
    /**
     * Email Priority
     * options: null (default), 1 = High, 3 = Normal, 5 = low. 
     * When null the header is not set at all 
     * 
     * @var int
     */
    public $Priority = null;


    /**
     * Mime Version of the mailer
     * Default 1
     * 
     * @var int
     */
    public $MimeVersion = 1;


    /**
     * Mail addresser/from
     * 
     * @var string
     */
    public $From = '';


    /**
     * Mail Reply to address
     * Will default to the From address, though can be overwritten
     * for a different reply to address.
     */
    public $ReplyTo = '';


    /**
     * Return-path for errors
     * Defaults to From field
     * 
     * @var string
     */
    public $ReturnPath = '';


    /**
     * Mail adressee/to
     * 
     * @var string
     */
    public $To = '';


    /**
     * Mail subject
     * 
     * @var string
     */
    public $Subject = '';


    /**
     * Mail message body
     * can format with html, if default content type is used
     * 
     * @var string
     */
    public $Message = '';


    /**
     * Additional headers aside from to, subject and message
     * 
     * @var string
     */
    public $AddtionalHeaders = array();


    /**
     * Constructor
     */
    function __construct($To, $Subject, $Message)
    {
        $this->To = $To;
        $this->Subject = $Subject;
        $this->Message = $Message;
    }

    /**
     * Initialise some defaults
     */
    function initialise()
    {
        $this->ReplyTo = $this->From;
        $this->ReturnPath = $this->From;
        $XMailer .= phpversion();
    }


    /**
     * Set the additional headers array values
     */
    function set_additional_headers()
    {
        $this->AdditionalHeaders = array (
            'From' => $this->FromName . '< ' . $this->FromAddress . ' >' . $this->LF,
            'Reply-To' => $this->ReplyTo . $this->LF,
            'Return-Path' => $this->ReturnPath . $this->LF,
            'X-Sender' => $this->SenderName . '< ' . $this->SenderAddress . ' >' . $this->LF,
            'X-Mailer' => $this->XMailer . $this->LF,
            'X-Priority' => $this->Priority . $this->LF,
            'Content-Type' => $this->ContentType . '; ' . 'charset=' . $this->CharSet . $this->LF,
            'MIME-Version' => $this->MimeVersion . $this->LF,
        );    
    }


    function send()
    {
        /**
         * Minimum validation so far, just check to ensure we have valid email
         * addresses in the to and from properties.
         * This should really be extended to ensure fields that default to $From
         * haven't been overwritten with invalid values.
         */
        if(!filter_var($this->To, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('To property needs to be a valid email address');
        }

        if(!filter_var($this->From, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('From property needs to be a valid email address');
        }

        $this->initialise();

        /* Set the additional headers now incase any values have been overwritten */
        $this->set_additional_headers();

        mail($this->To, $Subject, $Message, $this->AddtionalHeaders);
    }
    
}

?>