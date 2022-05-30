import React, {useState, useEffect} from 'react';
import axios from "axios";
import globeVar from '../../GlobeApi'
import { useParams } from "react-router-dom"; 
function Pages() {
       // Get ID from UR
         const params = useParams(); 
            
            const [posts, setPosts] = useState([])
            
                useEffect(()=> {
                  // const cms_id= sessionStorage.getItem("cmsid");
                   
                     axios.get(globeVar+`cms/${params.id}`)
                    .then(res => {
                        console.log(res.data.data)
                        setPosts(res.data.data)
                    })
                    .catch(err =>{
                        console.log(err)
                    }) 
                }, [])  
    
  return (
    <div>{JSON.stringify(posts)}</div>
  )
}
export default Pages