//import './NavBar.css';
import 'bootstrap/dist/css/bootstrap.css';

import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
  
  export const NavBar = () => {
  return (
    <div class="NavBar">
      <Navbar expand="lg" className="bg-body-tertiary" bg="primary" data-bs-theme="dark">
        <Container>
          <Navbar.Brand href="/home">Blue Express</Navbar.Brand>
          <Navbar.Toggle aria-controls="basic-navbar-nav" />
          <Navbar.Collapse id="basic-navbar-nav">
            <Nav className="me-auto">
              <Nav.Link href="/home">Home</Nav.Link>
              <Nav.Link href="/contactus">Contact Us</Nav.Link>
              <Nav.Link href="/about">About</Nav.Link>
              <Nav.Link href="/trackshipment">Track Shipment</Nav.Link>
              <NavDropdown title="More Options" id="basic-nav-dropdown">
                <NavDropdown.Item href="#action/3.1">Action1</NavDropdown.Item>
                <NavDropdown.Item href="#action/3.2">
                  Action2
                </NavDropdown.Item>
                <NavDropdown.Item href="#action/3.3">Action3</NavDropdown.Item>
                <NavDropdown.Divider />
                <NavDropdown.Item href="#action/3.4">
                  Some More Actions
                </NavDropdown.Item>
              </NavDropdown>
            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>
    </div>
  
  );
}