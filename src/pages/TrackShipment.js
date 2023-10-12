import './TrackShipment.css';

import { Form, Placeholder, InputGroup, Button, Card } from 'react-bootstrap';
import { useEffect, useState } from 'react';


function Load(){
  const [isLoading, setLoading] = useState(false);
    useEffect(() => {
      function simulateNetworkRequest() {
        return new Promise((resolve) => setTimeout(resolve, 2000));
      }
  
      if (isLoading) {
        simulateNetworkRequest().then(() => {
          setLoading(false);
        });
      }
    }, [isLoading]);
  
    const handleClick = () => setLoading(true);
    
}

export const TrackShipment = () => {
  const [isLoading, setLoading] = useState(false);
  useEffect(() => {
    function simulateNetworkRequest() {
      return new Promise((resolve) => setTimeout(resolve, 2000));
    }

    if (isLoading) {
      simulateNetworkRequest().then(() => {
        setLoading(false);
      });
    }
  }, [isLoading]);

  const handleClick = () => setLoading(true);

  const shipmentData = {
    shipmentId: 'AbcXyztt1515',
    bookDateTime: '12:55 PM',
    sourceAddress: "Raipur",
    destinationAddress:"Pune",
    currentStatus: "In Transit",
    expectedDeliveryDate: "12-10-2025",
    courierPartner: "Ablos Courier Services"
  };
  
  return (            
    <>      
        <div className="d-flex justify-content-around">
          <Card style={{ width: '180rem' }}>            
            <Card.Body>
              <Card.Title>Enter Shipment ID</Card.Title>
              <Card.Text>
              To track your consignment please enter any combination 
              of up to 25 tracking numbers, seperated by comma:
              </Card.Text>
              <InputGroup className="mb-3">          
                <Form.Control id="basic-url"  placeholder="Example: AbcXyztt1515" aria-describedby="basic-addon3" />
              </InputGroup>
              <Button
                variant="primary"
                //disabled={isLoading}
                onClick={!isLoading ? handleClick : null}
                //{isLoading ? 'Loading…' : 'Click to load'}
              >
                Track Shipment
              </Button>
            </Card.Body>
          </Card>
        </div>  

        <div className="d-flex justify-content-around">
          <Card style={{ width: '180rem' }}>      
                
            <Card.Body>
              <Card.Title>Shipment Details</Card.Title>              
              <ul>
                <li>
                  <strong>Shipment ID:</strong> {shipmentData.shipmentId}
                </li>
                <li>
                  <strong>Book Date & Time:</strong> {shipmentData.bookDateTime}
                </li>
                <li>
                  <strong>Source Address:</strong> {shipmentData.sourceAddress}
                </li>
                <li>
                  <strong>Destination Address:</strong> {shipmentData.destinationAddress}
                </li>
                <li>
                  <strong>Current Status:</strong> {shipmentData.currentStatus}
                </li>
                <li>
                  <strong>Expected Date of Delivery:</strong> {shipmentData.expectedDeliveryDate}
                </li>
                <li>
                  <strong>Courier Partner:</strong> {shipmentData.courierPartner}
                </li>
              </ul>                            
            </Card.Body>
          </Card>
        </div>        
 
     </>   
  
  );
}
