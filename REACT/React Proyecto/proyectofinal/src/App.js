import React from 'react';
import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import Menu from './misc/Menu';
import Footer from './misc/Footer';
import UserList from './user/UserList';
import AlbumList from './Album/AlbumList'; 
import ArtistList from './Artist/ArtistList';

function App() {
  return (
    <div className="container-fluid">
      <div className="container-fluid">
        <Menu />
      </div>
      <div className="container-fluid">

        <UserList />
        <AlbumList /> 
        <ArtistList />

      </div>
      <div className="container-fluid">
        <Footer />
      </div>
    </div>
  );
}

export default App;
