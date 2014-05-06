<?php
class logfile{
    function write($the_string )
    {
        if( $fh = @fopen( "../../tmp/logs/genre_combine.log", "a+" ) )
        {
            fputs( $fh, $the_string, strlen($the_string) );
            fclose( $fh );
            return( true );
        }
        else
        {
            return( false );
        }
    }
}
?>