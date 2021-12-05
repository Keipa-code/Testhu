import React, {FC, useEffect} from 'react';
import {useRootStore} from "../../RootStateContext";
import {ActionMeta, OnChangeValue} from "react-select";
import {TagOption} from "./TagsFormStore";
import CreatableSelect from "react-select/creatable";
import {observer} from "mobx-react-lite";
import {Button} from "react-bootstrap";
import {NewTestStore} from "../../pages/NewTest/NewTestStore";
import AsyncCreatableSelect from "react-select/async-creatable";


interface TagsFormProps {
    addTags?: NewTestStore["addTags"];
}
const TagsForm: FC<TagsFormProps> = observer(({addTags}) => {
    const {tagsFormStore} = useRootStore()

    useEffect(() => {
        //tagsFormStore.fetchTags()
    }, [])

    const handleChange = (
        newValue: OnChangeValue<TagOption, true>,
        actionMeta: ActionMeta<TagOption>
    ) => {
        const id = (newValue.length - 1)
        tagsFormStore.addSelectedTags(newValue[id], actionMeta.action)
    }

    return (
        <div>
            <AsyncCreatableSelect
                isMulti
                cacheOptions
                defaultOptions
                value={tagsFormStore.selectedTags}
                getOptionLabel={e => e.label}
                getOptionValue={e => String(e.id)}
                onChange={handleChange}
                loadOptions={tagsFormStore.fetchTags}
            />
            <Button variant="success" onClick={() => {
                console.log(tagsFormStore.selectedTags)
            }}>Фетч</Button>
        </div>
    );
});

export default TagsForm;