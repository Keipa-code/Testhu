import React, {FC} from 'react';
import {ITest} from "../types/types";
import {Card, Table} from "react-bootstrap";

interface TestsListProps {
    tests: ITest[]
}

const TestsList: FC<TestsListProps> = ({tests}) => {
    return (
        <Table>
            <tbody>
            {tests.map(test =>
                <tr key={test.id}>
                    <td>
                        <Card style={{ width: '18rem', border: 0 }}>
                            <Card.Body>
                                <Card.Title>{test.testName}</Card.Title>
                                {test.tags.map(tag =>
                                    <Card.Link key={tag.id} href="#">{tag.tagName}</Card.Link>
                                )}
                                </Card.Body>
                        </Card>
                    </td>
                    <td>
                        {test.passed} / {test.done}
                    </td>
                    <td>
                        <a href='#'>&#9658;</a>
                    </td>
                </tr>
            )}
            </tbody>
        </Table>
    );
};

export default TestsList;