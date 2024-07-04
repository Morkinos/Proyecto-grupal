import React, { useState } from 'react';
import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import Menu from './misc/Menu';
import Footer from './misc/Footer';
import UserList from './user/UserList';
import AlbumList from './Album/AlbumList';
import ArtistList from './Artist/ArtistList';
import PurchaseList from './Purchase/PurchaseList';

function App() {
  const [activeComponent, setActiveComponent] = useState(''); // Estado para el componente activo

  // Función para renderizar el componente activo
  const renderActiveComponent = () => {
    switch (activeComponent) {
      case 'User':
        return <UserList />;
      case 'Album':
        return <AlbumList />;
      case 'Artist':
        return <ArtistList />;
      case 'Purchase':
        return <PurchaseList />;
      case 'Home':
        return (
          <div className="text-center">
            <h1>Bienvenido a la página principal</h1>
            <img src="FondoApp.png" alt="Descripción de la imagen" className="img-fluid" />
          </div>
        );
    }
  };

  return (
    <div id="root" className="d-flex flex-column min-vh-100">
    <div className="container-fluid bg-purple flex-grow-1">
      <div className="container-fluid">
        <Menu activeComponent={activeComponent} setActiveComponent={setActiveComponent} />
      </div>
      <div className="container-fluid">
        {renderActiveComponent()}
      </div>
    </div>
    <div className="footer-container">
      <Footer />
    </div>
  </div>
 
  );
}

export default App;
