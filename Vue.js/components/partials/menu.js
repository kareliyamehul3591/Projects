import stores from '@/stores';

let modules= stores.modules.items;
let tempModules=[],counter=1;
for (let i=0; i< modules.length; i++){
  let module = modules[i];
  
  console.log('itration', module);
  if(module.type === 'VOD'){
    tempModules[counter] = {
      id: counter,
      label: "menuitems.on-demand.text",
      icon: "fa-tv",
      subItems: [
        {
          id: counter+1,
          label: "menuitems.on-demand.movies.text",
          link: "/tv/"+ module.id +"/channels",
          parentId: counter
        },
        {
          id: counter+2,
          label: "menuitems.on-demand.categories.text",
          link: "/tv/"+ module.id +"/categories",
          parentId: counter
        }
      ]
    };
    counter += 3;
  }else if(module.type === 'PAGES'){
    tempModules[counter] = {
      id: counter,
      label: 'menuitems.' + element.name + '.text',
      icon: "fa-tv",
      subItems: [
        {
          id: counter+1,
          label: "'menuitems.page.items.text'",
          link: "/pages/"+module.id+"/pages_items",
          parentId: counter
        },
        {
          id: counter+2,
          label: "menuitems.page.categories.text",
          link: "/pages/"+module.id+"/categories/0",
          parentId: counter
        }
      ]
    };
    counter += 3;
  }else if(module.type === 'TV_RADIO'){
    tempModules[counter] = {
      id: counter,
      label: 'menuitems.tv.text',
      icon: "fa-tv",
      subItems: [
        {
          id: counter+1,
          label: "menuitems.tv.channels.text",
          link: "/tv/"+module.id+"/channels",
          parentId: counter
        },
        {
          id: counter+2,
          label: "menuitems.tv.categories.text",
          link: "/tv/"+module.id+"/categories",
          parentId: counter
        }
      ]
    };
    counter += 3;
  }
}

console.log('Menus', tempModules);
export const menuItems = tempModules;

