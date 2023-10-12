import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { TrackShipment } from '../pages/TrackShipment';

export const Routing = () => {
    return (
        <BrowserRouter>
            <Routes>                
                <Route path="/" element={<TrackShipment />} exact />
                <Route path="/trackshipment" element={<TrackShipment />} />
            </Routes>
        </BrowserRouter>
    );
}
  

/*function Routing(){
    return(
        <BrowserRouter>
            <Routes>
                <Route path="/trackshipment:shipmentId">
                    <TrackShipment />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}

export default Routing;*/