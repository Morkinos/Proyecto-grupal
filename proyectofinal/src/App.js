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
      default:
        return <div>Seleccione una opción del menú</div>;
    }
  };

  return (
    <div className="container-fluid">
      <div className="container-fluid">
        <Menu activeComponent={activeComponent} setActiveComponent={setActiveComponent} />
      </div>
      <div className="container-fluid">
        {renderActiveComponent()}
      </div>
      <div className="container-fluid">
        <Footer />
      </div>
    </div>
  );
}

export default App;
