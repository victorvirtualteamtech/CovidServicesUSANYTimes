<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CovidController
{
    private $states = ["Washington","Illinois", "California", "Arizona", "Massachusetts", "Wisconsin", "Texas", "Nebraska", "Utah", "Oregon", "Florida", "New York", "Rhode Island", "Georgia", "New Hampshire", "North Carolina", "New Jersey", "Colorado", "Maryland", "Nevada", "Tennessee", "Hawaii", "Indiana", "Kentucky", "Minnesota", "Oklahoma", "Pennsylvania", "South Carolina", "District of Columbia", "Kansas", "Missouri", "Vermont", "Virginia", "Connecticut", "Iowa", "Louisiana", "Ohio", "Michigan", "South Dakota", "Arkansas", "Delaware", "Mississippi", "New Mexico", "North Dakota", "Wyoming", "Alaska", "Maine", "Alabama", "Idaho", "Montana", "Puerto Rico", "Virgin Islands", "Guam", "West Virginia", "Northern Mariana Islands", "American Samoa"];

    /**
     * [Eng] Filter covid information depending on the city and / or dates settings
     *
     * @param array json data
     * @return array json data
    */
    public function covidFilters(Request $request)
    {
        $dataCovid = Cache::get('dataCovid' );

        if ( $dataCovid ) {

            $responseData = [];
            if ( $request->state ) {

                $responseData[ $request->state ]["cases"] = array_sum(array_column( $this->filterDataStateDate($dataCovid, $request->state, $request->date) , 'cases')); 
                $responseData[ $request->state ]["deaths"] = array_sum(array_column( $this->filterDataStateDate($dataCovid, $request->state, $request->date) , 'deaths')); 
 
            } else {

                for ( $i = 0 ; $i < count($this->states); $i++ ) {    

                    $responseData[ $this->states[$i] ]["cases"] = array_sum(array_column($this->filterDataStateDate($dataCovid, $this->states[$i], $request->date) , 'cases')); 
                    $responseData[ $this->states[$i] ]["deaths"] = array_sum(array_column($this->filterDataStateDate($dataCovid, $this->states[$i], $request->date) , 'deaths')); 
                    
                }

            }
            return response()->json( ['message' => 'Succeed', 'data' => $responseData, 'code'=> 200] );

        } else {

            return response()->json( ['message' => 'Failed: No data', 'data' => $dataCovid, 'code'=> 401] );

        }
    }

    private function filterDataStateDate($dataCovid, $state, $date = NULL) {
        return array_filter($dataCovid, function($v, $k) use($state, $date)  {
            return !isset($date) ? $v['state'] == $state : $v['state'] == $state && strtotime($v['date']) >= strtotime($date); 
        }, ARRAY_FILTER_USE_BOTH);
    }


    /**
     * [Eng] Upload Covid information from New York Time to Laravel cache
     *
     * @param array $data
     * @return json
    */
    public function loadDataFromNyTimes() {
        try {
            $dataCovid = Cache::remember( 'dataCovid', now()->addMinutes(1440), function() {
                $data = file_get_contents("https://raw.githubusercontent.com/nytimes/covid-19-data/master/us-states.csv");
                $dataReturn = [];
                $rows = explode("\n",$data);

                $idCount = 0;
                foreach($rows as $row) {
                    if ( $idCount++ > 0 ) {
                        $data = str_getcsv($row);
                        $dataReturn[] = [
                            'id' => $idCount,
                            'date' => $data[0],
                            'datestamp' => strtotime($data[0]),
                            'state' => $data[1],
                            'cases' => $data[3],
                            'deaths' => $data[4],
                        ];
                    }
                }  
                return $dataReturn;
            });

            if ( $dataCovid ) {

                return response()->json( ['message' => 'Succeed', 'data' => $dataCovid, 'code'=> 200] );

            } else {

                return response()->json( ['message' => 'Failed: No data', 'data' => $dataCovid, 'code'=> 401] );

            }

        } catch (\Throwable $th) {

            return response()->json(['message' => $th , 'message' => $th,]);

        }
    }


    /**
     * [Eng] Returns states of USA
     *
     * @param array json data
     * @return array json data
    */
    public function states() {
        return response()->json( ['message' => 'Succeed', 'data' => $this->states, 'code'=> 200] );
    }    



}