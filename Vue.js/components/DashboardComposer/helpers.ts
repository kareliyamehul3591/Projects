import {DashboardPanel, PanelElement} from '@/stores/Dashboard';

export function createItemPanelElement(module: IModule, item: IItem) {
    console.log('module', module);
    const panelElement = new PanelElement();
    panelElement.linkModuleId = module.id!;
    panelElement.linkElementId = item.id;
    panelElement.name = item.name!;
    panelElement.linkLayout = 'element';
    panelElement.linkAction = 'play';
    panelElement.image = item.image ? item.image.imageUrl : null;
    panelElement.imageId = item.image ? item.image.id : null;
    panelElement.active = true;
    if (module.name === 'GuestFlow') {
      panelElement.linkAction = module.name;
      // @ts-ignore
      panelElement.linkModuleId = module.moduleId;
    }

    if (module.type === 'APPLICATION') {
        panelElement.linkLayout = 'application';
        panelElement.linkAction = item.type;
        panelElement.linkElementId = item.packageName;
    } else if (module.type === 'SERIES') {
        panelElement.linkLayout = 'series';
    }
    return panelElement;
}

export function createModulePanelElement(module: IModule)  {
    console.log('module', module);
    const panelElement = new PanelElement({
        name: module.name,
        // @ts-ignore
        linkModuleId: module.id,
        // @ts-ignore
        linkAction: module.sort ? 'GuestFlow' : null,
        linkLayout: 'element',
        // @ts-ignore
        imageId: module.imageId,
        // @ts-ignore
        image: module.image ? module.image.imageUrl : '',
        active: true,
    });
    return panelElement;
}

export function createModulesPanel(modules: IModule[], elementType = 'image') {
    const panel = new DashboardPanel();
    panel.name = 'eServices';
    panel.elementType = elementType;
    for (const module of modules) {
        const panelElement = new PanelElement({
            name: module.name,
            linkModuleId: module.id,
            linkLayout: 'element',
            active: true,
        });
        panel.elements.push(panelElement);
    }
    return panel;

}
