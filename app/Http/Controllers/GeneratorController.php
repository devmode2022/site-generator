<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;
use App\Http\Requests\SiteGeneratorRequest;
use File;

class GeneratorController extends Controller
{

    /**
     * Function to generate WP Site
     * 
     * @param SiteGeneratorRequest $request
     */
    public function index(SiteGeneratorRequest $request)
    {
        $app_url = config('app.url');

        $sitename = $request->sitename;
        $admin_email = $request->admin_email;
        $admin_user = $request->admin_user;
        $admin_pass = $request->admin_pass;
        
        $dbname = 'hoa_' . Str::random(5);
        $dbuser = 'root';

        $output = null;
        $retval = null;

        $theme = $request->theme ?? "hello-elementor";

        if( File::exists( $sitename ) ) {
            return response()->json([
                'message' => 'The site name is already taken. Please choose another site name',
                'status' => 0
            ], 200);
        }

        /**
         * Remove the command set PATH=%PATH%;C:\laragon\bin\mysql\mysql-5.7.33-winx64\bin; when it is live as this is only for windows to fix the error regarding mysql * being undefined
         */
        exec( 'cd public', $output, $retval);
        exec( 'mkdir ' . $sitename, $output, $retval );
        exec( "cd $sitename && wp core download && set PATH=%PATH%;C:\laragon\bin\mysql\mysql-5.7.33-winx64\bin; && wp config create --dbname=$dbname --dbuser=$dbuser && wp db create --defaults && wp core install --url=$app_url/$sitename --title=$sitename --admin_user=$admin_user --admin_password=$admin_pass --admin_email=$admin_email && cd wp-content/themes && git clone " . config("themes.$theme.repo") . " && wp theme activate " . config("themes.$theme.name") . "", $output, $retVal );

        if( $retVal == 1 ) {
            return response()->json([
                'message' => 'Something went wrong'
            ]);
        }

        
        return response()->json([
            'message'   => 'Please visit your site follow this link ' . $app_url . '/' . $sitename,
            'admin_user' => $admin_user,
            'admin_pass' => $admin_pass,
            'output'    => $output,
            'status'    => $retVal,
        ], 200);
    }
}
